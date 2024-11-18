const handleFileUpload = async(files, access_token) => {

    if(files.length > 0){

        const url = 'http://localhost/Cookie%20Jar/api/file-upload.php'
        const formData = new FormData();

        for (const file of files) {
            formData.append('files[]', file);
        }

        try{
            const result = await fetch(url,
            {
                method: "POST",
                headers: {
                    'Authorization' : 'Bearer ' + access_token
                },
                body: formData
            })

            const responseData = await result.json();
            if(responseData[0]['success']){
                window.location.reload();
            }

        } catch(err){
            console.error("File upload error:", err);
        }
    }
}