var $j = jQuery.noConflict();

$j(document).ready(function() {
  $j('#multiple-checkboxes').multiselect({
    includeSelectAllOption: true,
  });
});