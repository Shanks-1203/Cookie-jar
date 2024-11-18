const fetchDownloadUrl = async (id, name, access_token) => {
    try {
        const response = await fetch(`http://localhost/Cookie%20Jar/api/file-download.php?id=${id}&name=${name}`,{
            method: 'GET',
            headers: {
                'Authorization' : 'Bearer ' + access_token
            }
        });

        const data = await response.json();

        if (data.downloadUrl) {
            const downloadUrl = data.downloadUrl;
            const link = document.createElement('a');
            link.href = downloadUrl;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        } else {
            console.log("Failed to retrieve download URL.");
        }
    } catch (error) {
        console.error("Error fetching download URL:", error);
    }
};