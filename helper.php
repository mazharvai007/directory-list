<?php

function scanAllDir($dir) {
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
                foreach (scanAllDir($filePath) as $childFilename) {
                    $result[] = $filename . '/' . $childFilename;
            }
            } else {
                $result[] = $filename;
            }
        }        
    }
    return $result;
}

function tagFilter($path) {
    
    $classTag = '';
    foreach ($path as $key => $value) {
        $filesArray = explode('/', $value);
        foreach ($filesArray as $key => $item) {
            if ($key != count($filesArray) - 1) {
                $classTag .= ' .' . $item;
            }
        }
    }
    return $classTag;
}

function tagsFiltering($dir, $allData=array()) {

    $invisibleFileNames = array(".", "..", "_thumbs");

    $dirContent = scandir($dir);
    foreach($dirContent as $key => $content) {

        $path = $dir.'/'.$content;
        if(!in_array($content, $invisibleFileNames)) {

            if(is_file($path) && is_readable($path)) {
                $filearray = explode('/', $path);
                $classTag = '';
                foreach($filearray as $key=>$item){
                    if($key != count($filearray)-1){
                        $classTag .=' ' . $item;
                    }
                }
                $allData[] = $classTag;
                // $allData[] = $path;

            }elseif(is_dir($path) && is_readable($path)) {
                $allData = tagsFiltering($path, $allData);
            }
        }
    }
    return $allData;
}


function folders($path, $filter = '.', $recurse = false, $full = false, $exclude = array('.svn', 'CVS', '.DS_Store', '__MACOSX'),
		$excludefilter = array('^\..*'))
	{
		// Check to make sure the path valid and clean
		// $pathObject = new PathWrapper;
		// $path = $pathObject->clean($path);

		// Is the path a folder?
		// if (!is_dir($path))
		// {
		// 	Log::add(Text::sprintf('JLIB_FILESYSTEM_ERROR_PATH_IS_NOT_A_FOLDER_FOLDER', $path), Log::WARNING, 'jerror');

		// 	return false;
		// }

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
		$arr = _items($path, $filter, $recurse, $full, $exclude, $excludefilter_string, false);

		// Sort the folders
		asort($arr);

		return array_values($arr);
    }
    
    function _items($path, $filter, $recurse, $full, $exclude, $excludefilter_string, $findfiles)
	{
		@set_time_limit(ini_get('max_execution_time'));

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
						$arr = array_merge($arr, _items($fullpath, $filter, $recurse - 1, $full, $exclude, $excludefilter_string, $findfiles));
					}
					else
					{
						$arr = array_merge($arr, _items($fullpath, $filter, $recurse, $full, $exclude, $excludefilter_string, $findfiles));
					}
				}
			}
		}

		closedir($handle);

		return $arr;
	}    