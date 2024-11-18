<?php

    session_start();
    require_once 'vendor/autoload.php';
    use GuzzleHttp\Client;

    function iconSelect($type) {
        switch ($type) {
            case "audio": return './Images/music.png';
            case "video": return './Images/video.png';
            case "image": return './Images/image.png';
            case "document": return './Images/document.png';
            default: return './Images/file.png';
        }
    }

    $client = new Client();
    $response = $client->get('http://localhost/Cookie%20Jar/api/uploaded-files-data.php', [
        'headers' => [
            'Authorization' => 'Bearer ' . $_SESSION['access_token'],
            'FromLocation' => true,
        ],
    ]);

    $data = json_decode($response->getBody(), true);
    if($data['error']){
        header("Location: login.php");
    }
?>

<div class="w-[59%] min-h-screen p-[2rem]">
    <?php include_once 'search.php';?>

    <p class="mt-[3rem] font-medium text-xl flex items-center justify-between">Recently Added <a href="myfiles.php"><span class="text-blue-500 hover:underline text-sm cursor-pointer">View all</span></a></p>
    <div class="w-full flex overflow-x-auto recents gap-12 mt-6">
        <?php if($data['warning']){?>
            <div class="flex flex-col justify-center items-center gap-[1rem] w-full h-[13rem] opacity-50">
                <img src="./Images/empty.png" alt="empty" class="w-[4rem]">
                <p>No uploads yet</p>
            </div>
        <?php } else {
            foreach ($data['uploads'] as $index => $file) {
            ?>
                <div class="cursor-pointer w-[13rem]">
                    <div class="w-[13rem] h-[13rem] grid place-items-center bg-[#80808020] relative overflow-hidden" onmouseenter="onHover(<?php echo $index; ?>)" onmouseleave="onLeave(<?php echo $index; ?>)">
                        <img src="<?php echo iconSelect($file['type']);?>" alt="icon" class="w-[3rem]">
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
        } ?>
    </div>

    <input type="file" id="fileInput" style="display: none;" onchange="handleFileChange(event.target.files)" multiple>

    <div class="mt-[3rem] w-full h-[15rem] border-2 border-blue-300 relative border-dashed rounded-md bg-blue-50 flex flex-col justify-center items-center gap-2" ondragenter="dragOver(event)" ondragleave="dragLeave()" ondrop="onDrop(event)">
        <img src="./Images/upload.png" alt="upload icon" class="w-[3rem] opacity-50 before-selection">
        <p class="text-[#00000065] before-selection">Drag and drop files or <span class="text-blue-500 font-medium cursor-pointer hover:underline" onclick="document.getElementById('fileInput').click()">Browse.</span></p>
        <p id="select-msg" class="text-blue-500 after-selection hidden">No files selected.</p>
        <img src="./Images/box.png" alt="drop files now" class="w-[3rem] opacity-50 during-drag hidden">
        <p class="text-blue-500 during-drag hidden">You drop, we catch!</p>
        <div class="flex gap-[1rem] items-center absolute bottom-4 right-4 after-selection hidden">
            <p class="p-[0.7rem] bg-white border-2 border-blue-400 rounded-md cursor-pointer" title="Add more" onclick="document.getElementById('fileInput').click()"><img src="./Images/plus.png" alt="add" class="w-[1.5rem]"></p>
            <p class="p-[0.7rem] bg-blue-500 border-2 border-white rounded-md cursor-pointer" title="Upload" onclick="handleFileUpload(files, '<?php echo $_SESSION['access_token'];?>')"><img src="./Images/tick.png" alt="add" class="w-[1.5rem] invert"></p>
        </div>
        <p class="p-[0.7rem] bg-red-500 border-2 border-white rounded-md cursor-pointer absolute bottom-4 left-4 after-selection hidden" title="Clear selection" onclick="clearSelection()"><img src="./Images/plus.png" alt="add" class="w-[1.5rem] invert rotate-45"></p>
    </div>
</div>

<script>
    let files = [];

    const clearSelection = () => {
        files = [];

        document.querySelectorAll('.after-selection').forEach(msg => msg.classList.add('hidden'));
        document.querySelectorAll('.before-selection').forEach(msg => msg.classList.remove('hidden'));
    }

    const handleFileChange = (sentFiles) => {
        const selectedFiles = Array.from(sentFiles);
        files.push(...selectedFiles);

        document.querySelectorAll('.after-selection').forEach(msg => msg.classList.remove('hidden'));
        document.querySelectorAll('.before-selection').forEach(msg => msg.classList.add('hidden'));
        
        document.getElementById('select-msg').innerHTML = files.length + ' files selected.';
    }

    const setDelete = (id, name, size, type) => {
        document.getElementById('delete-popup').classList.remove('scale-0');

        setFileId = id;
        setFileName = name;
        setFileSize = size;
        setFileType = type;
    }

    const dragOver = (event) => {
        event.preventDefault();
        document.querySelectorAll('.before-selection').forEach(msg => msg.classList.add('hidden'));
        document.querySelectorAll('.during-drag').forEach(msg => msg.classList.remove('hidden'));
    }

    const dragLeave = () => {
        document.querySelectorAll('.before-selection').forEach(msg => msg.classList.remove('hidden'));
        document.querySelectorAll('.during-drag').forEach(msg => msg.classList.add('hidden'));
    }

    const onDrop = (event) => {
        event.preventDefault();
        const files = event.dataTransfer.files;
        document.querySelectorAll('.during-drag').forEach(msg => msg.classList.add('hidden'));
        handleFileChange(files);
    }

</script>
<script src="./scripts/file-upload.js"></script>
<script src="./scripts/file-download.js"></script>
<script src="./scripts/more-option.js"></script>
