<?php
    if(isset($_GET["query"])){
        $query = $_GET["query"];
    }
?>

<form class="w-[40rem] rounded-md border-2 border-[#80808040] flex items-center gap-4 px-4 py-2" action="" method="get">
    <img src="./Images/search.png" alt="search" class="w-[1.5rem] opacity-50">
    <input type="text" name="query" class="outline-none h-full w-full bg-white" placeholder="Search..." value="<?php echo $query;?>">
</form>
