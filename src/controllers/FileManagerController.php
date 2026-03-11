<?php

namespace crocodicstudio\crudbooster\controllers;

use Exception;
use stdClass;
use UploadHandler;

class FileManagerController extends \crocodicstudio\crudbooster\controllers\CBController
{
    public function __construct()
    {
        //$this->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend');
    }

    public function index()
    {
        return view('crudbooster::filamanager.filemanager');
    }

    public function execute()
    {
        $config = include base_path() . '/vendor/voila_cms/crudbooster/src/filemanager/includes/config/config.php';

        include base_path() . '/vendor/voila_cms/crudbooster/src/filemanager/includes/include/utils.php';

        if ($_SESSION['RF']["verify"] != "RESPONSIVEfilemanager") {
            response(trans('forbidden') . AddErrorLocation())->send();
            exit;
        }

        if (!checkRelativePath($_POST['path'])) {
            response(trans('wrong path') . AddErrorLocation())->send();
            exit;
        }

        if (isset($_SESSION['RF']['language']) && file_exists(base_path() . '/vendor/voila_cms/crudbooster/src/filemanager/includes/lang/' . basename((string) $_SESSION['RF']['language']) . '.php')) {
            $languages = include base_path() . '/vendor/voila_cms/crudbooster/src/filemanager/includes/lang/languages.php';
            if (array_key_exists($_SESSION['RF']['language'], $languages)) {
                include base_path() . '/vendor/voila_cms/crudbooster/src/filemanager/includes/lang/' . basename((string) $_SESSION['RF']['language']) . '.php';
            } else {
                response(trans('Lang_Not_Found') . AddErrorLocation())->send();
                exit;
            }
        } else {
            response(trans('Lang_Not_Found') . AddErrorLocation())->send();
            exit;
        }

        $ftp = ftp_con($config);

        $base = $config['current_path'];
        $path = $base . $_POST['path'];
        $cycle = true;
        $max_cycles = 50;
        $i = 0;

        while ($cycle && $i < $max_cycles) {
            $i++;
            if ($path == $base) {
                $cycle = false;
            }

            if (file_exists($path . "config.php")) {
                require_once $path . "config.php";
                $cycle = false;
            }
            $path = fix_dirname($path) . "/";
        }

        function returnPaths($_path, $_name, $config)
        {
            global $ftp;
            $path = $config['current_path'] . $_path;
            $path_thumb = $config['thumbs_base_path'] . $_path;
            $name = null;
            if ($ftp) {
                $path = $config['ftp_base_folder'] . $config['upload_dir'] . $_path;
                $path_thumb = $config['ftp_base_folder'] . $config['ftp_thumbs_dir'] . $_path;
            }
            if ($_name) {
                $name = fix_filename($_name, $config);
                if (str_contains($name, '../') || str_contains($name, '..\\')) {
                    response(trans('wrong name') . AddErrorLocation())->send();
                    exit;
                }
            }
            return [$path, $path_thumb, $name];
        }

        if (isset($_POST['paths'])) {
            $paths = $paths_thumb = $names = [];
            foreach ($_POST['paths'] as $key => $path) {
                if (!checkRelativePath($path)) {
                    response(trans('wrong path') . AddErrorLocation())->send();
                    exit;
                }
                $name = null;
                if (isset($_POST['names'][$key])) {
                    $name = $_POST['names'][$key];
                }
                [$path, $path_thumb, $name] = returnPaths($path, $name, $config);
                $paths[] = $path;
                $paths_thumb[] = $path_thumb;
                $names = $name;
            }
        } else {
            $name = null;
            if (isset($_POST['name'])) {
                $name = $_POST['name'];
            }
            [$path, $path_thumb, $name] = returnPaths($_POST['path'], $name, $config);
        }

        $info = pathinfo((string) $path);
        if (
            isset($info['extension']) && !(isset($_GET['action']) && $_GET['action'] == 'delete_folder') &&
            !check_extension($info['extension'], $config)
            && $_GET['action'] != 'create_file'
        ) {
            response(trans('wrong extension') . AddErrorLocation())->send();
            exit;
        }

        if (isset($_GET['action'])) {
            switch ($_GET['action']) {
                case 'delete_file':

                    deleteFile($path, $path_thumb, $config);

                    break;

                case 'delete_files':
                    foreach ($paths as $key => $p) {
                        deleteFile($p, $paths_thumb[$key], $config);
                    }

                    break;
                case 'delete_folder':
                    if ($config['delete_folders']) {

                        if ($ftp) {
                            deleteDir($path, $ftp, $config);
                            deleteDir($path_thumb, $ftp, $config);
                        } else {
                            if (is_dir($path_thumb)) {
                                deleteDir($path_thumb, null, $config);
                            }

                            if (is_dir($path)) {
                                deleteDir($path, null, $config);
                                if ($config['fixed_image_creation']) {
                                    foreach ($config['fixed_path_from_filemanager'] as $paths) {
                                        if ($paths != "" && $paths[strlen($paths) - 1] != "/") {
                                            $paths .= "/";
                                        }

                                        $base_dir = $paths . substr_replace($path, '', 0, strlen((string) $config['current_path']));
                                        if (is_dir($base_dir)) {
                                            deleteDir($base_dir, null, $config);
                                        }

                                    }
                                }
                            }
                        }
                    }
                    break;
                case 'create_folder':
                    if ($config['create_folders']) {

                        $name = fix_filename($_POST['name'], $config);
                        $path .= $name;
                        $path_thumb .= $name;
                        $res = create_folder(fix_path($path, $config), fix_path($path_thumb, $config), $ftp, $config);
                        if (!$res) {
                            response(trans('Rename_existing_folder') . AddErrorLocation())->send();
                        }
                    }
                    break;
                case 'rename_folder':
                    if ($config['rename_folders']) {
                        if (!is_dir($path)) {
                            response(trans('wrong path') . AddErrorLocation())->send();
                            exit;
                        }
                        $name = fix_filename($name, $config);
                        $name = str_replace('.', '', $name);

                        if (!empty($name)) {
                            if (!rename_folder($path, $name, $ftp, $config)) {
                                response(trans('Rename_existing_folder') . AddErrorLocation())->send();
                                exit;
                            }
                            rename_folder($path_thumb, $name, $ftp, $config);
                            if (!$ftp && $config['fixed_image_creation']) {
                                foreach ($config['fixed_path_from_filemanager'] as $paths) {
                                    if ($paths != "" && $paths[strlen($paths) - 1] != "/") {
                                        $paths .= "/";
                                    }

                                    $base_dir = $paths . substr_replace($path, '', 0, strlen((string) $config['current_path']));
                                    rename_folder($base_dir, $name, $ftp, $config);
                                }
                            }
                        } else {
                            response(trans('Empty_name') . AddErrorLocation())->send();
                            exit;
                        }
                    }
                    break;

                case 'create_file':
                    if ($config['create_text_files'] === false) {
                        response(sprintf(trans('File_Open_Edit_Not_Allowed'), strtolower(trans('Edit'))) . AddErrorLocation())->send();
                        exit;
                    }

                    if (!isset($config['editable_text_file_exts']) || !is_array($config['editable_text_file_exts'])) {
                        $config['editable_text_file_exts'] = [];
                    }

                    // check if user supplied extension
                    if (!str_contains((string) $name, '.')) {
                        response(trans('No_Extension') . ' ' . sprintf(trans('Valid_Extensions'), implode(', ', $config['editable_text_file_exts'])) . AddErrorLocation())->send();
                        exit;
                    }

                    // correct name
                    $old_name = $name;
                    $name = fix_filename($name, $config);
                    if (empty($name)) {
                        response(trans('Empty_name') . AddErrorLocation())->send();
                        exit;
                    }

                    // check extension
                    $parts = explode('.', $name);
                    if (!in_array(end($parts), $config['editable_text_file_exts'])) {
                        response(trans('Error_extension') . ' ' . sprintf(trans('Valid_Extensions'), implode(', ', $config['editable_text_file_exts'])) . AddErrorLocation(), 400)->send();
                        exit;
                    }

                    $content = $_POST['new_content'];

                    if ($ftp) {
                        $temp = tempnam('/tmp', 'RF');
                        file_put_contents($temp, $content);
                        $ftp->put("/" . $path . $name, $temp, FTP_BINARY);
                        unlink($temp);
                        response(trans('File_Save_OK'))->send();
                    } else {
                        if (!checkresultingsize(strlen((string) $content))) {
                            response(sprintf(trans('max_size_reached'), $config['MaxSizeTotal']) . AddErrorLocation())->send();
                            exit;
                        }
                        // file already exists
                        if (file_exists($path . $name)) {
                            response(trans('Rename_existing_file') . AddErrorLocation())->send();
                            exit;
                        }

                        if (@file_put_contents($path . $name, $content) === false) {
                            response(trans('File_Save_Error') . AddErrorLocation())->send();
                            exit;
                        } else {
                            if (is_function_callable('chmod') !== false) {
                                chmod($path . $name, 0644);
                            }
                            response(trans('File_Save_OK'))->send();
                            exit;
                        }
                    }

                    break;

                case 'rename_file':
                    if ($config['rename_files']) {
                        $name = fix_filename($name, $config);
                        if (!empty($name)) {
                            if (!rename_file($path, $name, $ftp, $config)) {
                                response(trans('Rename_existing_file') . AddErrorLocation())->send();
                                exit;
                            }

                            rename_file($path_thumb, $name, $ftp, $config);

                            if ($config['fixed_image_creation']) {
                                $info = pathinfo((string) $path);

                                foreach ($config['fixed_path_from_filemanager'] as $k => $paths) {
                                    if ($paths != "" && $paths[strlen($paths) - 1] != "/") {
                                        $paths .= "/";
                                    }

                                    $base_dir = $paths . substr_replace($info['dirname'] . "/", '', 0, strlen((string) $config['current_path']));
                                    if (file_exists($base_dir . $config['fixed_image_creation_name_to_prepend'][$k] . $info['filename'] . $config['fixed_image_creation_to_append'][$k] . "." . $info['extension'])) {
                                        rename_file($base_dir . $config['fixed_image_creation_name_to_prepend'][$k] . $info['filename'] . $config['fixed_image_creation_to_append'][$k] . "." . $info['extension'], $config['fixed_image_creation_name_to_prepend'][$k] . $name . $config['fixed_image_creation_to_append'][$k], $ftp, $config);
                                    }
                                }
                            }
                        } else {
                            response(trans('Empty_name') . AddErrorLocation())->send();
                            exit;
                        }
                    }
                    break;
                    case 'edit_alt_text':
                        if ($config['edit_alt_text']) {
                            $alt = $_POST['alt'];
                            if (!empty($alt)) {
                                if (!edit_alt_text($path,$name ,$alt, $ftp, $config)) {
                                    response(trans('Edit alternate text done') . AddErrorLocation())->send();
                                    exit;
                                }
                            } else {
                                response(trans('Empty alternate text') . AddErrorLocation())->send();
                                exit;
                            }
                        }
                        break;
                case 'duplicate_file':
                    if ($config['duplicate_files']) {
                        $name = fix_filename($name, $config);
                        if (!empty($name)) {
                            if (!$ftp && !checkresultingsize(filesize($path))) {
                                response(sprintf(trans('max_size_reached'), $config['MaxSizeTotal']) . AddErrorLocation())->send();
                                exit;
                            }
                            if (!duplicate_file($path, $name, $ftp, $config)) {
                                response(trans('Rename_existing_file') . AddErrorLocation())->send();
                                exit;
                            }

                            duplicate_file($path_thumb, $name, $ftp, $config);

                            if (!$ftp && $config['fixed_image_creation']) {
                                $info = pathinfo((string) $path);
                                foreach ($config['fixed_path_from_filemanager'] as $k => $paths) {
                                    if ($paths != "" && $paths[strlen($paths) - 1] != "/") {
                                        $paths .= "/";
                                    }

                                    $base_dir = $paths . substr_replace($info['dirname'] . "/", '', 0, strlen((string) $config['current_path']));

                                    if (file_exists($base_dir . $config['fixed_image_creation_name_to_prepend'][$k] . $info['filename'] . $config['fixed_image_creation_to_append'][$k] . "." . $info['extension'])) {
                                        duplicate_file($base_dir . $config['fixed_image_creation_name_to_prepend'][$k] . $info['filename'] . $config['fixed_image_creation_to_append'][$k] . "." . $info['extension'], $config['fixed_image_creation_name_to_prepend'][$k] . $name . $config['fixed_image_creation_to_append'][$k]);
                                    }
                                }
                            }
                        } else {
                            response(trans('Empty_name') . AddErrorLocation())->send();
                            exit;
                        }
                    }
                    break;

                case 'paste_clipboard':
                    if (
                        !isset($_SESSION['RF']['clipboard_action'], $_SESSION['RF']['clipboard']['path'])
                        || $_SESSION['RF']['clipboard_action'] == ''
                        || $_SESSION['RF']['clipboard']['path'] == ''
                    ) {
                        response()->send();
                        exit;
                    }

                    $action = $_SESSION['RF']['clipboard_action'];
                    $data = $_SESSION['RF']['clipboard'];

                    if ($ftp) {
                        if ($_POST['path'] != "") {
                            $path .= DIRECTORY_SEPARATOR;
                            $path_thumb .= DIRECTORY_SEPARATOR;
                        }
                        $path_thumb .= basename((string) $data['path']);
                        $path .= basename((string) $data['path']);
                        $data['path_thumb'] = DIRECTORY_SEPARATOR . $config['ftp_base_folder'] . $config['ftp_thumbs_dir'] . $data['path'];
                        $data['path'] = DIRECTORY_SEPARATOR . $config['ftp_base_folder'] . $config['upload_dir'] . $data['path'];
                    } else {
                        $data['path_thumb'] = $config['thumbs_base_path'] . $data['path'];
                        $data['path'] = $config['current_path'] . $data['path'];
                    }

                    $pinfo = pathinfo($data['path']);

                    // user wants to paste to the same dir. nothing to do here...
                    if ($pinfo['dirname'] == rtrim((string) $path, DIRECTORY_SEPARATOR)) {
                        response()->send();
                        exit;
                    }

                    // user wants to paste folder to it's own sub folder.. baaaah.
                    if (is_dir($data['path']) && str_contains((string) $path, $data['path'])) {
                        response()->send();
                        exit;
                    }

                    // something terribly gone wrong
                    if ($action != 'copy' && $action != 'cut') {
                        response(trans('wrong action') . AddErrorLocation())->send();
                        exit;
                    }
                    if ($ftp) {
                        if ($action == 'copy') {
                            $tmp = time() . basename($data['path']);
                            $ftp->get($tmp, $data['path'], FTP_BINARY);
                            $ftp->put(DIRECTORY_SEPARATOR . $path, $tmp, FTP_BINARY);
                            unlink($tmp);

                            if (url_exists($data['path_thumb'])) {
                                $tmp = time() . basename((string) $data['path_thumb']);
                                try {
                                    $ftp->get($tmp, $data['path_thumb'], FTP_BINARY);
                                    $ftp->put(DIRECTORY_SEPARATOR . $path_thumb, $tmp, FTP_BINARY);
                                } catch (\Throwable $e) {
                                    \Log::warning('FTP operation error: ' . $e->getMessage());
                                }
                                unlink($tmp);
                            }
                        } elseif ($action == 'cut') {
                            $ftp->rename($data['path'], DIRECTORY_SEPARATOR . $path);
                            if (url_exists($data['path_thumb'])) {
                                try {
                                    $ftp->rename($data['path_thumb'], DIRECTORY_SEPARATOR . $path_thumb);
                                } catch (\Throwable $e) {
                                    \Log::warning('FTP rename error: ' . $e->getMessage());
                                }
                            }
                        }
                    } else {
                        // check for writability
                        if (is_really_writable($path) === false || is_really_writable($path_thumb) === false) {
                            response(trans('Dir_No_Write') . '<br/>' . str_replace('../', '', $path) . '<br/>' . str_replace('../', '', $path_thumb) . AddErrorLocation())->send();
                            exit;
                        }

                        // check if server disables copy or rename
                        if (is_function_callable(($action == 'copy' ? 'copy' : 'rename')) === false) {
                            response(sprintf(trans('Function_Disabled'), ($action == 'copy' ? (trans('Copy')) : (trans('Cut')))) . AddErrorLocation())->send();
                            exit;
                        }
                        if ($action == 'copy') {
                            [$sizeFolderToCopy, $fileNum, $foldersCount] = folder_info($path, false);
                            if (!checkresultingsize($sizeFolderToCopy)) {
                                response(sprintf(trans('max_size_reached'), $config['MaxSizeTotal']) . AddErrorLocation())->send();
                                exit;
                            }
                            rcopy($data['path'], $path);
                            rcopy($data['path_thumb'], $path_thumb);
                        } elseif ($action == 'cut') {
                            rrename($data['path'], $path);
                            rrename($data['path_thumb'], $path_thumb);

                            // cleanup
                            if (is_dir($data['path'])) {
                                rrename_after_cleaner($data['path']);
                                rrename_after_cleaner($data['path_thumb']);
                            }
                        }
                    }

                    // cleanup
                    $_SESSION['RF']['clipboard']['path'] = null;
                    $_SESSION['RF']['clipboard_action'] = null;

                    break;

                case 'chmod':
                    $mode = $_POST['new_mode'];
                    $rec_option = $_POST['is_recursive'];
                    $valid_options = ['none', 'files', 'folders', 'both'];
                    $chmod_perm = ($_POST['folder'] ? $config['chmod_dirs'] : $config['chmod_files']);

                    // check perm
                    if ($chmod_perm === false) {
                        response(sprintf(trans('File_Permission_Not_Allowed'), (is_dir($path) ? (trans('Folders')) : (trans('Files')))) . AddErrorLocation())->send();
                        exit;
                    }
                    // check mode
                    if (!preg_match("/^[0-7]{3}$/", (string) $mode)) {
                        response(trans('File_Permission_Wrong_Mode') . AddErrorLocation())->send();
                        exit;
                    }
                    // check recursive option
                    if (!in_array($rec_option, $valid_options)) {
                        response(trans("wrong option") . AddErrorLocation())->send();
                        exit;
                    }
                    // check if server disabled chmod
                    if (!$ftp && is_function_callable('chmod') === false) {
                        response(sprintf(trans('Function_Disabled'), 'chmod') . AddErrorLocation())->send();
                        exit;
                    }

                    $mode = "0" . $mode;
                    $mode = octdec($mode);
                    if ($ftp) {
                        $ftp->chmod($mode, "/" . $path);
                    } else {
                        rchmod($path, $mode, $rec_option);
                    }

                    break;

                case 'save_text_file':
                    $content = $_POST['new_content'];
                    // $content = htmlspecialchars($content); not needed
                    // $content = stripslashes($content);

                    if ($ftp) {
                        $tmp = time();
                        file_put_contents($tmp, $content);
                        $ftp->put("/" . $path, $tmp, FTP_BINARY);
                        unlink($tmp);
                        response(trans('File_Save_OK'))->send();
                    } else {
                        // no file
                        if (!file_exists($path)) {
                            response(trans('File_Not_Found') . AddErrorLocation())->send();
                            exit;
                        }

                        // not writable or edit not allowed
                        if (!is_writable($path) || $config['edit_text_files'] === false) {
                            response(sprintf(trans('File_Open_Edit_Not_Allowed'), strtolower(trans('Edit'))) . AddErrorLocation())->send();
                            exit;
                        }

                        if (!checkresultingsize(strlen((string) $content))) {
                            response(sprintf(trans('max_size_reached'), $config['MaxSizeTotal']) . AddErrorLocation())->send();
                            exit;
                        }
                        if (@file_put_contents($path, $content) === false) {
                            response(trans('File_Save_Error') . AddErrorLocation())->send();
                            exit;
                        } else {
                            response(trans('File_Save_OK'))->send();
                            exit;
                        }
                    }

                    break;

                default:
                    response(trans('wrong action') . AddErrorLocation())->send();
                    exit;
            }
        }
    }

    public function upload()
    {
        ini_set('display_errors', '0');
        try {
            if (!isset($config)) {
                $config = include base_path() . '/vendor/voila_cms/crudbooster/src/filemanager/includes/config/config.php';
            }

            $resolveStoragePath = function ($path) {
                $path = (string) $path;
                if ($path === '') {
                    return $path;
                }

                // If path is already absolute (Windows drive or Unix root), keep it as is.
                $isWindowsAbsolute = strlen($path) > 2 && ctype_alpha($path[0]) && $path[1] === ':' && ($path[2] === '\\' || $path[2] === '/');
                $isRootedPath = $path[0] === '\\' || $path[0] === '/';
                if ($isWindowsAbsolute || $isRootedPath) {
                    return $path;
                }

                return rtrim(public_path(), '\\/') . DIRECTORY_SEPARATOR . ltrim($path, '\\/');
            };

            if ($_SESSION['RF']["verify"] != "RESPONSIVEfilemanager") {
                response(trans('forbidden') . AddErrorLocation(), 403)->send();
                exit;
            }
            include base_path() . '/vendor/voila_cms/crudbooster/src/filemanager/includes/include/utils.php';

            include base_path() . '/vendor/voila_cms/crudbooster/src/filemanager/includes/include/mime_type_lib.php';

            $ftp = ftp_con($config);

            if ($ftp) {
                $source_base = $config['ftp_base_folder'] . $config['upload_dir'];
                $thumb_base = $config['ftp_base_folder'] . $config['ftp_thumbs_dir'];
            } else {
                $source_base = $resolveStoragePath($config['current_path']);
                $thumb_base = $resolveStoragePath($config['thumbs_base_path']);
            }

            if (isset($_POST["fldr"])) {
                $_POST['fldr'] = str_replace('undefined', '', $_POST['fldr']);
                $storeFolder = $source_base . $_POST["fldr"];
                $storeFolderThumb = $thumb_base . $_POST["fldr"];
            } else {
                return;
            }

            $fldr = rawurldecode(trim(strip_tags($_POST['fldr']), "/") . "/");

            if (!checkRelativePath($fldr)) {
                response(trans('wrong path') . AddErrorLocation())->send();
                exit;
            }

            $path = $storeFolder;
            $cycle = true;
            $max_cycles = 50;
            $i = 0;
            //GET config
            while ($cycle && $i < $max_cycles) {
                $i++;
                if ($path == $config['current_path']) {
                    $cycle = false;
                }
                if (file_exists($path . "config.php")) {
                    $configTemp = include $path . 'config.php';
                    $config = array_merge($config, $configTemp);
                    //TODO switch to array
                    $cycle = false;
                }
                $path = fix_dirname($path) . '/';
            }

            require base_path() . '/vendor/voila_cms/crudbooster/src/filemanager/includes/UploadHandler.php';
            $messages = null;
            if (trans("Upload_error_messages") !== "Upload_error_messages") {
                $messages = trans("Upload_error_messages");
            }

            // make sure the length is limited to avoid DOS attacks
            if (isset($_POST['url']) && strlen($_POST['url']) < 2000) {
                $url = $_POST['url'];
                $urlPattern = '/^(https?:\/\/)?([\da-z\.-]+\.[a-z\.]{2,6}|[\d\.]+)([\/?=&#]{1}[\da-z\.-]+)*[\/\?]?$/i';

                if (preg_match($urlPattern, (string) $url)) {
                    $temp = tempnam('/tmp', 'RF');

                    $ch = curl_init($url);
                    $fp = fopen($temp, 'wb');
                    curl_setopt($ch, CURLOPT_FILE, $fp);
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    curl_exec($ch);
                    if (curl_errno($ch) !== 0) {
                        curl_close($ch);
                        throw new Exception('Invalid URL');
                    }
                    curl_close($ch);
                    fclose($fp);
                    $_FILES['files'] = [
                        'name' => [basename($_POST['url'])],
                        'tmp_name' => [$temp],
                        'size' => [filesize($temp)],
                        'type' => null,
                    ];
                } else {
                    throw new Exception('Is not a valid URL.');
                }
            }

            // Ensure $_FILES['files'] is properly set
            if (!isset($_FILES['files']) || !is_array($_FILES['files'])) {
                throw new Exception('No files uploaded');
            }

            if (!isset($_FILES['files']['name'][0])) {
                throw new Exception('Invalid file upload data');
            }

            $mime_type = isset($_FILES['files']['type'][0]) ? $_FILES['files']['type'][0] : null;

            if ($config['mime_extension_rename']) {
                $info = pathinfo((string) $_FILES['files']['name'][0]);
                $mime_type = $_FILES['files']['type'][0] ?? null;
                if (isset($_FILES['files']['tmp_name'][0])) {
                    if (function_exists('mime_content_type')) {
                        $mime_type = mime_content_type($_FILES['files']['tmp_name'][0]);
                    } elseif (function_exists('finfo_open')) {
                        $finfo = finfo_open(FILEINFO_MIME_TYPE);
                        $mime_type = finfo_file($finfo, $_FILES['files']['tmp_name'][0]);
                    } else {
                        $mime_type = get_file_mime_type($_FILES['files']['tmp_name'][0]);
                    }
                }
                $extension = get_extension_from_mime($mime_type ?? '');

                if ($extension == 'so' || $extension == '' || $mime_type == "text/troff") {
                    $extension = $info['extension'] ?? '';
                }
                $filename = ($info['filename'] ?? 'file') . "." . $extension;
            } else {
                $filename = $_FILES['files']['name'][0] ?? 'file';
            }
            $_FILES['files']['name'][0] = fix_filename($filename, $config);

            if (!($_FILES['files']['type'][0] ?? null)) {
                $_FILES['files']['type'][0] = $mime_type;
            }
            // LowerCase
            if ($config['lower_case']) {
                $_FILES['files']['name'][0] = fix_strtolower($_FILES['files']['name'][0]);
            }
            if (!checkresultingsize(($_FILES['files']['size'][0] ?? 0))) {
                $errorResponse = [
                    'files' => [
                        [
                            'name' => $_FILES['files']['name'][0] ?? null,
                            'size' => $_FILES['files']['size'][0] ?? 0,
                            'type' => $_FILES['files']['type'][0] ?? null,
                            'error' => sprintf(trans('max_size_reached'), $config['MaxSizeTotal']) . AddErrorLocation(),
                        ],
                    ],
                ];
                echo json_encode($errorResponse);
                exit();
            }

            $uploadConfig = [
                'config' => $config,
                'storeFolder' => $storeFolder,
                'storeFolderThumb' => $storeFolderThumb,
                'ftp' => $ftp,
                'upload_dir' => $storeFolder,
                'upload_url' => $config['base_url'] . $config['upload_dir'] . $_POST['fldr'],
                'mkdir_mode' => $config['folderPermission'],
                'max_file_size' => $config['MaxSizeUpload'] * 1024 * 1024,
                'correct_image_extensions' => true,
                'print_response' => false,
            ];

            $escapeRegexParts = static function ($extensions) {
                if (!is_array($extensions)) {
                    return [];
                }

                $extensions = array_filter($extensions, static function ($ext) {
                    return $ext !== null && $ext !== '';
                });

                return array_map(static function ($ext) {
                    return preg_quote((string) $ext, '/');
                }, array_values($extensions));
            };

            if (!$config['ext_blacklist']) {
                $allowedExt = $escapeRegexParts($config['ext'] ?? []);
                $uploadConfig['accept_file_types'] = $allowedExt
                    ? '/\.(' . implode('|', $allowedExt) . ')$/i'
                    : '/\.[A-Za-z0-9]+$/i';

                if ($config['files_without_extension']) {
                    $uploadConfig['accept_file_types'] = $allowedExt
                        ? '/((\.(' . implode('|', $allowedExt) . ')$)|(^[^.]+$))$/i'
                        : '/(^[^.]+$)|\.[A-Za-z0-9]+$/i';
                }
            } else {
                $blockedExt = $escapeRegexParts($config['ext_blacklist'] ?? []);
                $uploadConfig['accept_file_types'] = $blockedExt
                    ? '/\.(?!' . implode('|', $blockedExt) . '$)/i'
                    : '/\.[A-Za-z0-9]+$/i';

                if ($config['files_without_extension']) {
                    $uploadConfig['accept_file_types'] = $blockedExt
                        ? '/((\.(?!' . implode('|', $blockedExt) . '$))|(^[^.]+$))/i'
                        : '/(^[^.]+$)|\.[A-Za-z0-9]+$/i';
                }
            }

            if ($ftp) {
                if (!is_dir($config['ftp_temp_folder'])) {
                    mkdir($config['ftp_temp_folder'], $config['folderPermission'], true);
                }

                if (!is_dir($config['ftp_temp_folder'] . "thumbs")) {
                    mkdir($config['ftp_temp_folder'] . "thumbs", $config['folderPermission'], true);
                }

                $uploadConfig['upload_dir'] = $config['ftp_temp_folder'];
            }
            // print_r($_FILES);die();
            $upload_handler = new UploadHandler($uploadConfig, true, $messages);
        } catch (Exception $e) {
            $return = [];
            if (isset($_FILES['files']) && is_array($_FILES['files']) && isset($_FILES['files']['name']) && is_array($_FILES['files']['name'])) {
                foreach ($_FILES['files']['name'] as $i => $name) {
                    $return[] = [
                        'name' => $name,
                        'error' => $e->getMessage(),
                        'size' => $_FILES['files']['size'][$i] ?? 0,
                        'type' => $_FILES['files']['type'][$i] ?? null,
                    ];
                }
                echo json_encode(["files" => $return]);
                return;
            }
            echo json_encode(["error" => $e->getMessage()]);
        }
    }
    public function forceDownload()
    {
        $config = include base_path() . '/vendor/voila_cms/crudbooster/src/filemanager/includes/config/config.php';

        include base_path() . '/vendor/voila_cms/crudbooster/src/filemanager/includes/include/utils.php';
        include base_path() . '/vendor/voila_cms/crudbooster/src/filemanager/includes/include/mime_type_lib.php';

        if ($_SESSION['RF']["verify"] != "RESPONSIVEfilemanager") {
            response(trans('forbidden') . AddErrorLocation(), 403)->send();
            exit;
        }
        if (!checkRelativePath($_POST['path']) || str_starts_with((string) $_POST['path'], '/')) {
            response(trans('wrong path') . AddErrorLocation(), 400)->send();
            exit;
        }
        if (str_contains((string) $_POST['name'], '/')) {
            response(trans('wrong path') . AddErrorLocation(), 400)->send();
            exit;
        }

        $ftp = ftp_con($config);
        if ($ftp) {
            $path = $config['ftp_base_url'] . $config['upload_dir'] . $_POST['path'];
        } else {
            $path = $config['current_path'] . $_POST['path'];
        }

        $name = $_POST['name'];
        $info = pathinfo((string) $name);

        if (!check_extension($info['extension'], $config)) {
            response(trans('wrong extension') . AddErrorLocation(), 400)->send();
            exit;
        }

        $file_name = $info['basename'];
        $file_ext = $info['extension'];
        $file_path = $path . $name;

        // make sure the file exists
        if ($ftp) {
            header('Content-Type: application/octet-stream');
            header("Content-Transfer-Encoding: Binary");
            header("Content-disposition: attachment; filename=\"" . $file_name . "\"");
            readfile($file_path);
        } elseif (is_file($file_path) && is_readable($file_path)) {
            if (!file_exists($path . $name)) {
                response(trans('File_Not_Found') . AddErrorLocation(), 404)->send();
                exit;
            }

            $size = filesize($file_path);
            $file_name = rawurldecode($file_name);

            if (function_exists('mime_content_type')) {
                $mime_type = mime_content_type($file_path);
            } elseif (function_exists('finfo_open')) {
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime_type = finfo_file($finfo, $file_path);
            } else {
                $mime_type = get_file_mime_type($file_path);
            }

            @ob_end_clean();
            if (ini_get('zlib.output_compression')) {
                ini_set('zlib.output_compression', 'Off');
            }
            header('Content-Type: ' . $mime_type);
            header('Content-Disposition: attachment; filename="' . $file_name . '"');
            header("Content-Transfer-Encoding: binary");
            header('Accept-Ranges: bytes');
            if (isset($_SERVER['HTTP_RANGE'])) {
                [$a, $range] = explode("=", (string) $_SERVER['HTTP_RANGE'], 2);
                [$range] = explode(",", $range, 2);
                [$range, $range_end] = explode("-", $range);
                $range = intval($range);
                $range_end = $range_end === '' || $range_end === '0' ? $size - 1 : intval($range_end);
                $new_length = $range_end - $range + 1;
                header("HTTP/1.1 206 Partial Content");
                header("Content-Length: $new_length");
                header("Content-Range: bytes $range-$range_end/$size");
            } else {
                $new_length = $size;
                header("Content-Length: " . $size);
            }
            $chunksize = (1024 * 1024);
            $bytes_send = 0;
            if ($file = fopen($file_path, 'r')) {
                if (isset($_SERVER['HTTP_RANGE'])) {
                    fseek($file, $range);
                }
                while (
                    !feof($file) &&
                    (!connection_aborted()) &&
                    ($bytes_send < $new_length)
                ) {
                    $buffer = fread($file, $chunksize);
                    echo $buffer;
                    flush();
                    $bytes_send += strlen($buffer);
                }
                fclose($file);
            } else {
                die('Error - can not open file.');
            }
            die();
        } else {
            // file does not exist
            header("HTTP/1.0 404 Not Found");
        }
        exit;
    }

    public function ajaxCall()
    {
        return view('crudbooster::filamanager.ajax_calls');
    }
}
