<?php

/* * ****************************************************************** */
/*     Client  Name  :  IFRC                                      */
/*     Project Name  :  cms v3.0                                     */
/*     Program Name  :  MyAccess.php                                      */
/*     Entry Server  :                                               */
/*     Called By     :  System                                       */
/*     Notice        :                                              */
/*     Copyright     :  IFRC                                         */
/* ------------------------------------------------------------------- */
/*     Comment       :  hook access                             */
/* ------------------------------------------------------------------- */
/*     History       :                                               */
/* ------------------------------------------------------------------- */
/*     Version V001  :  2012.08.27 (LongNguyen)(Tung)                      */
/* * ****************************************************************** */

class Access {

    public $CI;
    public $Zend_Acl;
    public $role;
    public $role_id;

    public function __construct() {
        $this->CI = & get_instance();
        // load zend Acl
        $this->CI->zend->load('Zend_Acl');
        $this->Zend_Acl = new Zend_Acl;
    }

    /*     * ************************************************************** */
    /*    Name ： start                                                 */
    /* --------------------------------------------------------------- */
    /*    Description  ：  this function will be called automatically  */
    /*                   when controller system start ,                */
    /*                   check user access controller                 */
    /* --------------------------------------------------------------- */
    /*    Params  ：  None                                             */
    /* --------------------------------------------------------------- */
    /*    Return  ：                                                 */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                  */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.08.28 (LongNguyen)(Tung)                            */
    /*     * ************************************************************** */

    public function start() {
        // get current module

        $module = $this->CI->router->fetch_module();
        // get current controller
        $controller = $this->CI->router->fetch_class();
        // get current action
        $action = $this->CI->router->fetch_method();
        if ($module != 'auth') {
            // set default role
            $role = 'guest';
            // check if user login then get role with user_id
            if ($this->CI->ion_auth->logged_in() == TRUE) {
                // get user id
                $user_id = $this->CI->ion_auth->user()->row()->user_id;
                // get role
                $roles = $this->CI->ion_auth->get_users_roles($user_id)->row();
                if ($roles != FALSE) {
                    // if exists set role = new role
                    $role = $roles->name;
                    $this->role_id = $roles->role_id;
                }
            } else {
                $role_id = $this->CI->db->select('role_id')->get_where('role', array('name' => $role))->row_array();
                if (is_array($role_id) == TRUE && count($role_id) > 0) {
                    $this->role_id = $role_id['role_id'];
                }
            }
            $this->role = $role;
            // call action
            $this->setRoles();
            $this->setResources();
            $this->setAccess();
            // try check allowed ACl
            try {
                if ($this->Zend_Acl->isAllowed($role, $module . ':' . $controller, $action) == FALSE) {
                    $this->CI->session->set_userdata('access', 'You do not have permission to access <strong>' . $controller . ' ' . $action . '</strong>, please refer to your system administrator.');
                    $link = base_url();
                    if ($module == 'admin') {
                        $link = $this->CI->config->item('admin_url') . '/access_denied';
                    }
                    redirect($link, 'refresh');
                }
            } catch (Exception $ex) {
                die($ex->getMessage());
            }
        }
    }

    /*     * ************************************************************** */
    /*    Name ： setRoles                                                  */
    /* --------------------------------------------------------------- */
    /*    Description  ：  auto set role for zend acle with data       */
    /*                     in databasse  table role                    */
    /* --------------------------------------------------------------- */
    /*    Params  ：  None                                             */
    /* --------------------------------------------------------------- */
    /*    Return  ：                                                 */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                  */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.08.28 (LongNguyen)(Tung)                    */
    /*     * ************************************************************** */

    public function setRoles() {
        $roles = $this->CI->db->select('name')->get('role')->result();
        if ($roles != FALSE && count($roles) > 0)
            foreach ($roles as $value) {
                $this->Zend_Acl->addRole(new Zend_Acl_Role($value->name));
            }
    }

    /*     * ************************************************************** */
    /*    Name ： setResources                                             */
    /* --------------------------------------------------------------- */
    /*    Description  ：  auto set Resources for zend acle with data       */
    /*                     in databasse table resource                         */
    /* --------------------------------------------------------------- */
    /*    Params  ：  None                                             */
    /* --------------------------------------------------------------- */
    /*    Return  ：                                                 */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                  */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.08.28 (LongNguyen)(Tung)                    */
    /*     * ************************************************************** */

    public function setResources() {
        $resources = $this->CI->db->select('module,controller')->get('resource')->result();
        if ($resources != FALSE && count($resources) > 0)
            foreach ($resources as $value) {
                $this->Zend_Acl->add(new Zend_Acl_Resource($value->module . ':' . $value->controller));
            }
    }

    /*     * ************************************************************** */
    /*    Name ： accesss                                               */
    /* --------------------------------------------------------------- */
    /*    Description  ：  auto set Resources for zend acle with data       */
    /*                     in databasse  table permission                */
    /* --------------------------------------------------------------- */
    /*    Params  ：  None                                             */
    /* --------------------------------------------------------------- */
    /*    Return  ：                                                 */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                  */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.08.28 (LongNguyen)(Tung)                    */
    /*     * ************************************************************** */

    public function setAccess() {
        $this->CI->db->select('*');
        $res = array();
        $this->CI->db->where('role_id', $this->role_id);
        $permission = $this->CI->db->get('permission')->row();
        $resources = $this->CI->db->select('module,controller,action')->get('resource')->result_array();
        // tao ra list array resource
        if ($resources != FALSE && count($resources) > 0) {
            foreach ($resources as $value) {
                $actions = explode('|', $value['action']);
                foreach ($actions as $action) {
                    $res[$value['module']][$value['controller']][$action] = $action;
                }
            }
        }
        // kien tra xem ton tai permission ko
        if ($permission != NULL) {
            //decode json
            $permission = json_decode($permission->value, true);
            foreach ($permission as $module => $controllers) {
                // kiem tra xem co foreach duoc ko
                if (count($controllers) > 0) {
                    foreach ($controllers as $controller => $actions) {
                        foreach ($actions as $action) {
                            // kiem tra permission co trong resource hay ko , neu co moi set access
                            if (isset($res[$module][$controller][$action])) {
                                $this->Zend_Acl->allow($this->role, array($module . ':' . $controller), $action);
                            }
                        }
                    }
                }
            }
        }
    }

}

?>