
<?php
  include_once 'helper.php';

  $folder = folders('images', $filter = '.', $recurse = true, $fullpath = false, $exclude = array('_thumbs'), $excludefilter = array('^\..*'));


  $images = scanAllDir('images');

 var_dump(tagsFiltering('images'));
 var_dump($folder);

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
              </ul>
          </div>
          <div>
              <ul class="uk-subnav uk-subnav-pill" uk-margin>
                <?php foreach ($folder as $key => $value) : ?>
                  <?php echo '<li uk-filter-control="[data-tag=' . $value . ']"><a href="#">' . $value . '</a></li>'; ?>
                <?php endforeach; ?>
              </ul>
          </div>
      </div>

      <ul class="js-filter uk-child-width-1-2 uk-child-width-1-3@m uk-text-center" uk-grid="masonry: true">
        <?php foreach ($images as $key => $value) : ?>
          <li data-tag="white" data-size="large">
              <div class="uk-card uk-card-default uk-card-body">
                  <img src="images/<?php echo $images[$key]; ?>" alt="">
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