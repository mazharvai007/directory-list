<?php

function getFiles($dir) {
    $result = [];

    $exclude = array('.svn', 'CVS','.DS_Store','__MACOSX', '_thumbs');
    $excludefilter = array('^\..*');

    if (count($excludefilter))
    {
        $excludefilter_string = '/(' . implode('|', $excludefilter) . ')/';
    }        

    foreach(scandir($dir) as $filename) {

        if ($filename != '.' && $filename != '..' && !in_array($filename, $exclude) && (empty($excludefilter_string) || !preg_match($excludefilter_string, $filename))) 
        {
            $filePath = $dir . '/' . $filename;     
            if (is_dir($filePath)) {
                foreach (getFiles($filePath) as $childFilename) {
                    $result[] = $filename . '/' . $childFilename;
            }
            } else {
                $result[] = $filename;
            }
        }        
    }
    return $result;
}

// function listDirs($dir) {
//     static $alldirs = array();
// 	$dirs = glob($dir . '/*', GLOB_ONLYDIR);

//     if (count($dirs) > 0) {
//         foreach ($dirs as $d) {
// 			$findFolder = explode('/', $d);
// 			$getFolder = '';
// 			foreach($findFolder as $key => $value) {
// 				if ($value == '_thumbs' && $findFolder[0]) {
// 					continue;
// 				} else {
// 					$getFolder = $value;
// 				}
// 			}			
// 			$alldirs[] = $getFolder;
// 		}
//     }
//     foreach ($dirs as $dir) {
// 		listDirs($dir);
// 	}

//     return $alldirs;
// }


function folders($path, $filter = '.', $recurse = true, $full = false, $exclude = array('.svn', 'CVS', '.DS_Store', '__MACOSX', '_thumbs'),
		$excludefilter = array('^\..*'))
	{
		// Compute the excludefilter string
		if (count($excludefilter))
		{
			$excludefilter_string = '/(' . implode('|', $excludefilter) . ')/';
		}
		else
		{
			$excludefilter_string = '';
		}

		// Get the folders
		$arr = items($path, $filter, $recurse, $full, $exclude, $excludefilter_string, false);

		// Sort the folders
		asort($arr);

		return array_values($arr);
    }
    
    function items($path, $filter, $recurse, $full, $exclude, $excludefilter_string, $findfiles)
	{

		$arr = array();

		// Read the source directory
		if (!($handle = @opendir($path)))
		{
			return $arr;
		}

		while (($file = readdir($handle)) !== false)
		{
			if ($file != '.' && $file != '..' && !in_array($file, $exclude)
			&& (empty($excludefilter_string) || !preg_match($excludefilter_string, $file)))
			{
				// Compute the fullpath
				$fullpath = $path . '/' . $file;

				// Compute the isDir flag
				$isDir = is_dir($fullpath);

				if (($isDir xor $findfiles) && preg_match("/$filter/", $file))
				{
					// (fullpath is dir and folders are searched or fullpath is not dir and files are searched) and file matches the filter
					if ($full)
					{
						// Full path is requested
						$arr[] = $fullpath;
					}
					else
					{
						// Filename is requested
						$arr[] = $file;
					}
				}

				if ($isDir && $recurse)
				{
					// Search recursively
					if (is_int($recurse))
					{
						// Until depth 0 is reached
						$arr = array_merge($arr, items($fullpath, $filter, $recurse - 1, $full, $exclude, $excludefilter_string, $findfiles));
					}
					else
					{
						$arr = array_merge($arr, items($fullpath, $filter, $recurse, $full, $exclude, $excludefilter_string, $findfiles));
					}
				}
			}
		}

		closedir($handle);

		return $arr;
	}    