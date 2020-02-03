
<?php
  include_once 'helper.php';


  $images = getFiles('images');

  $folder = folders("images");

  $all_selected_filters = array();
	foreach ($folder as $filters_key => $filters_value) {
    $filterArray = explode(',', $filters_value);
    if(is_array($filterArray)) {
      $all_selected_filters = array_merge($all_selected_filters, $filterArray);
    }    
  }

  getDirectories('images');

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Directory Gallery</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.3.0/dist/css/uikit.min.css" />

</head>
<body>

  <div class="uk-container">
  
    <div uk-filter="target: .js-filter">

      <div class="uk-grid-small uk-grid-divider uk-child-width-auto" uk-grid>
          <div>
              <ul class="uk-subnav uk-subnav-pill" uk-margin>  
                <li class="uk-active" uk-filter-control><a href="#">All</a></li>   

                <?php
                  if(is_array($all_selected_filters) && count($all_selected_filters)) {
                    foreach ($all_selected_filters as $filter_key) {
                      ?>
                      <li uk-filter-control="[data-tag='<?php echo $filter_key ?>']"><a href="#"><?php echo $filter_key; ?></a></li>
                      <?php
                    }
                  }
                ?>
              </ul>
          </div>
      </div>

      <ul class="js-filter uk-child-width-1-2 uk-child-width-1-3@m uk-text-center" uk-grid="masonry: true">
        <?php foreach ($images as $key => $value) : ?>
          <?php 
            $imgTag = '';

              $filesArray = explode('/', $value);
              foreach ($filesArray as $key => $item) {
                  if ($key != count($filesArray) - 1) {
                      $imgTag = $item . '';
                  }
              }   
                      
          ?>
          <li data-tag="<?php echo $imgTag; ?>">
              <div class="uk-card uk-card-default uk-card-body">
                  <img src="images/<?php echo $value; ?>" alt="">
                  <div class="uk-position-center">Item</div>
              </div>
          </li>
        <?php endforeach; ?>
      </ul>

    </div>  

  </div>

  <script src="https://cdn.jsdelivr.net/npm/uikit@3.3.0/dist/js/uikit.min.js"></script>
</body>
</html>