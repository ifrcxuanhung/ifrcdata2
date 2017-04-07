/*
Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/


CKEDITOR.editorConfig = function(config) {
   config.filebrowserBrowseUrl = $base_url+'assets/bundles/kcfinder/browse.php?type=files';
   config.filebrowserImageBrowseUrl = $base_url+'assets/bundles/kcfinder/browse.php?type=images';
   config.filebrowserFlashBrowseUrl = $base_url+'assets/bundles/kcfinder/browse.php?type=flash';
   config.filebrowserUploadUrl = $base_url+'assets/bundles/kcfinder/upload.php?type=files';
   config.filebrowserImageUploadUrl = $base_url+'assets/bundles/kcfinder/upload.php?type=images';
   config.filebrowserFlashUploadUrl = $base_url+'assets/bundles/kcfinder/upload.php?type=flash';
};