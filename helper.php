<?php

function getFiles($dir) {
    $result = [];

    $exclude = array('.svn', 'CVS','.DS_Store','__MACOSX', '_thumbs');
    $excludefilter = array('^\..*');
	$excludefilter_string = '/(' . implode('|', $excludefilter) . ')/';

    foreach(scandir($dir) as $filename) {

        if ($filename != '.' && $filename != '..' && !in_array($filename, $exclude) && !preg_match($excludefilter_string, $filename))
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

function getDirectories($dir)
{
	$items = glob($dir . '/*', GLOB_ONLYDIR);

		foreach ($items as $dir) {
			getDirectories($dir);
		}

	var_dump($items);

    return $items;
}


function folders($path, $filter = '.', $recurse = true, $full = false, $exclude = array('.svn', 'CVS', '.DS_Store', '__MACOSX', '_thumbs'),
		$excludefilter = array('^\..*'))
	{
		if (count($excludefilter))
		{
			$excludefilter_string = '/(' . implode('|', $excludefilter) . ')/';
		}
		else
		{
			$excludefilter_string = '';
		}

		$arr = items($path, $filter, $recurse, $full, $exclude, $excludefilter_string, false);

		asort($arr);

		return array_values($arr);
    }

    function items($path, $filter, $recurse, $full, $exclude, $excludefilter_string, $findfiles)
	{

		$arr = array();

		if (!($handle = @opendir($path)))
		{
			return $arr;
		}

		while (($file = readdir($handle)) !== false)
		{
			$ext = explode('.', $file);

			if ($file != '.' && $file != '..' && !in_array($file, $exclude) && (empty($excludefilter_string) || !preg_match($excludefilter_string, $file)))
			{

				$fullpath = $path . '/' . $file;


				$isDir = is_dir($fullpath);

				if (($isDir xor $findfiles) && preg_match("/$filter/", $file))
				{
					if ($full)
					{
						$arr[] = $fullpath;
					}
					else
					{
						$arr[] = $file;
					}
				}

				if ($isDir && $recurse)
				{
					if (is_int($recurse))
					{
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
