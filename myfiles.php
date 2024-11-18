<?php
    session_start();
    require_once 'vendor/autoload.php';
    require_once 'authMiddleware.php';
    use GuzzleHttp\Client;

    $userId = authenticate($_SESSION['access_token']);
    
    $client = new Client();
    $response = $client->get('http://localhost/Cookie%20Jar/api/uploaded-files-data.php', [
        'headers' => [
            'Authorization' => 'Bearer ' . $_SESSION['access_token'],
            'FromLocation' => false,
        ],
    ]);

    $data = json_decode($response->getBody(), true);
    if($data['error']){
        header("Location: login.php");
    }

    function iconSelect($type) {
        switch ($type) {
            case "audio": return './Images/music.png';
            case "video": return './Images/video.png';
            case "image": return './Images/image.png';
            case "document": return './Images/document.png';
            default: return './Images/file.png';
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cookie Jar-A Cloud Storage Solution</title>
    <link rel="stylesheet" href="more-option-style.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <div class="w-full flex justify-between">
        <div class="w-[16%]"><?php include_once 'sidebar.php';?></div>
        <div class="w-[84%] min-h-screen">
            <?php include_once 'myfiles-header.php'; ?>
            <div class="w-full flex flex-wrap gap-12 mt-6 px-[2rem]">
                <?php if($data['warning']){?>
                    <div class="flex flex-col justify-center items-center gap-[1rem] w-full h-[88vh] opacity-50">
                        <img src="./Images/empty.png" alt="empty" class="w-[4rem]">
                        <p>No uploads yet</p>
                    </div>
                <?php } else {
                    foreach($data['uploads'] as $index => $file){ ?>
                        <div class="cursor-pointer w-[13rem]">
                            <div class="w-[13rem] h-[13rem] grid place-items-center bg-[#80808020] relative overflow-hidden" onmouseenter="onHover(<?php echo $index; ?>)" onmouseleave="onLeave(<?php echo $index; ?>)">
                                <img src="<?php echo iconSelect($file['type']); ?>" alt="icon" class="w-[3rem]">
                                <div id="more-icon<?php echo $index;?>" class="transition-all top-[-1.3rem] right-[-5rem] closed-more" onclick="openMore(<?php echo $index; ?>)" onmouseleave="closeMore(<?php echo $index; ?>)">
                                    <img id="more-img<?php echo $index;?>" src="./Images/more.png" alt="more" class="w-[1.2rem] invert">
                                    <ul id="open-options<?php echo $index;?>" class="text-lg text-center flex flex-col items-center gap-4 hidden">
                                        <li class="text-white bg-blue-500 px-[1rem] py-[0.5rem] w-[8rem] rounded-md" onclick="fetchDownloadUrl(<?php echo $file['id'];?>, '<?php echo $file['upload_name'];?>', '<?php echo $_SESSION['access_token'];?>')">Download</li>
                                        <li class="text-white bg-red-500 px-[1rem] py-[0.5rem] w-[8rem] rounded-md" onclick="setDelete(<?php echo $file['id'];?>, '<?php echo $file['upload_name'];?>', <?php echo $file['size'];?>, '<?php echo $file['type'];?>')">Delete</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="mt-3">
                                <p class="font-medium"><?php echo $file['upload_name'];?></p>
                                <p class="opacity-65 text-sm mt-1">Added on <?php echo $file['upload_date'];?></p>
                            </div>
                        </div>
                    <?php }
                }?>
            </div>
        </div>
    </div>

    <input type="file" id="fileInput" style="display: none;" onchange="handleFileChange(event)" multiple>

    <div id="popup" class="fixed top-[50%] left-[50%] translate-x-[-50%] translate-y-[-50%] transition-all w-[30rem] h-[18rem] bg-blue-50 grid place-items-center scale-0">
        <p id="select-msg" class="text-blue-500">No files selected.</p>
        <div class="flex gap-[1rem] items-center absolute bottom-4 right-4 after-selection">
            <p class="p-[0.7rem] bg-white border-2 border-blue-400 rounded-md cursor-pointer" title="Add more" onclick="document.getElementById('fileInput').click()"><img src="./Images/plus.png" alt="add" class="w-[1.5rem]"></p>
            <p class="p-[0.7rem] bg-blue-500 border-2 border-white rounded-md cursor-pointer" title="Upload" onclick="handleFileUpload(files, '<?php echo $_SESSION['access_token'];?>')"><img src="./Images/tick.png" alt="add" class="w-[1.5rem] invert"></p>
        </div>
        <p class="p-[0.7rem] bg-red-500 border-2 border-white rounded-md cursor-pointer absolute bottom-4 left-4 after-selection" title="Clear selection" onclick="clearSelection()"><img src="./Images/plus.png" alt="add" class="w-[1.5rem] invert rotate-45"></p>
    </div>

    <div id="delete-popup" class="w-[25rem] h-[13rem] fixed top-[50%] left-[50%] transition-all translate-x-[-50%] translate-y-[-50%] bg-blue-100 rounded-md grid place-items-center py-[1rem] scale-0">
        <p class="text-blue-500">Are you sure you want to delete the file?</p>
        <div class="flex items-center gap-[3rem]">
            <p class="py-[0.7rem] px-[2rem] rounded-md bg-blue-500 text-white cursor-pointer" onclick="deleteRecord(setFileId, setFileName, '<?php echo $_SESSION['access_token'];?>', setFileSize, setFileType)">Yes</p>
            <p class="py-[0.7rem] px-[2rem] rounded-md bg-red-500 text-white cursor-pointer" onclick="closeDelete()">No</p>
        </div>
    </div>

<script>
    let files = [];

    let setFileId;
    let setFileName;
    let setFileSize;
    let setFileType;

    const closeDelete = () => {
        document.getElementById('delete-popup').classList.add('scale-0');

        setFileId = null;
        setFileName = null;
        setFileSize = null;
        setFileType = null;
    }

    const setDelete = (id, name, size, type) => {
        document.getElementById('delete-popup').classList.remove('scale-0');

        setFileId = id;
        setFileName = name;
        setFileSize = size;
        setFileType = type;
    }

    const clearSelection = () => {
        files = [];
        document.getElementById('popup').classList.add('scale-0')
    }

    const handleFileChange = (event) => {
        const selectedFiles = Array.from(event.target.files);
        files.push(...selectedFiles);

        document.getElementById('select-msg').innerHTML = files.length + ' files selected.';
        document.getElementById('popup').classList.remove('scale-0')
    }

</script>
<script src="./scripts/file-upload.js"></script>
<script src="./scripts/file-delete.js"></script>
<script src="./scripts/file-download.js"></script>
<script src="./scripts/more-option.js"></script>
</body>
</html>
