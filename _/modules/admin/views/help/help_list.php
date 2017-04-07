<article class="container_12">
    <section class="grid_4">
        <div class="block-border">
            <div class="block-content">
                <h1><?php trans('mn_topics'); ?></h1>
                <div class="block-controls">
                    <ul class="controls-buttons">
                        <li></li>
                        <li></li>
                    </ul>
                </div>
                <div id="loading"><img src="<?php echo base_url() . 'assets/images/loading.gif'; ?>" /></div>
                <ul id="menu" class="collapsible-list with-bg">
                    <?php
                    if (isset($list_cate) && is_array($list_cate)):
                        foreach ($list_cate as $list):
                            if($list->status == 1) {
                                ?>
                                <li class="closed list_help">
                                    <b class="toggle"></b>
                                    <span><?php echo $list->name; ?></span>
                                    <ul class="with-icon">
                                        <?php
                                        if (isset($article) == TRUE && is_array($article) == TRUE && count($article) > 0) {
                                            foreach ($article as $value) {
                                                if (isset($value) == TRUE && is_array($value) == TRUE && count($value) > 0) {
                                                    foreach ($value as $v) {
                                                        if ($list->category_id == $v['category_id']) {
                                                            ?>
                                                            <li class="article-detail" value="<?php echo $v['article_id']; ?>">
                                                                <a href="JavaScript: void(0)"><?php echo $v['title']; ?></a>
                                                            </li>
                                                            <?php
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                        ?>
                                    </ul>
                                </li>
                                <?php
                            }
                        endforeach;
                    endif;
                    ?>
                </ul>
            </div>
        </div>
    </section>
    <div id="reponse-detail"></div>
</article>