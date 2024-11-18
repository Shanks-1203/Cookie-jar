const deleteRecord = async (id, name, access_token, size, type) => {
    try {
        const response = await fetch('http://localhost/Cookie%20Jar/api/file-delete.php', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'Authorization' : 'Bearer ' + access_token
            },
            body: new URLSearchParams({ id, name, size, type })
        });

        const data = await response.json();
        
        if (data.success) {
            window.location.reload();
        } else {
            console.log("Failed to delete record:", data.error);
        }
    } catch (error) {
        console.error("Error:", error);
    }
};