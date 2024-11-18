<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login to CookieJar</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="w-full h-screen grid place-items-center bg-blue-100">
    <form class="w-[25%] h-[25%] p-[1rem] bg-white opacity-95 rounded-md grid place-items-center shadow-lg">
        <div>
            <p class="text-2xl text-center font-medium text-blue-500">CookieJar.</p>
            <p class="opacity-65 mt-1">A Cloud Storage Solution.</p>
        </div>
        <div onClick="loginWithGoogle()" class="flex justify-center items-center w-[85%] p-3 border-2 rounded-md cursor-pointer border-[#80808030] gap-3">
            <p class="font-medium text-blue-500">Continue to Google</p>
            <img src="./Images/google.png" alt="google" class="w-[1.5rem]">
        </div>
    </form>

    <script type="module">

        <?php
            require_once 'vendor/autoload.php';

            $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
            $dotenv->load();
        ?>
        
        import { initializeApp } from "https://www.gstatic.com/firebasejs/9.6.1/firebase-app.js";
        import { getAuth, GoogleAuthProvider, signInWithPopup } from "https://www.gstatic.com/firebasejs/9.6.1/firebase-auth.js";

        const firebaseConfig = {
            apiKey: "<?= $_ENV['API_KEY'] ?>",
            authDomain: "<?= $_ENV['AUTH_DOMAIN'] ?>",
            projectId: "<?= $_ENV['PROJECT_ID'] ?>",
            storageBucket: "<?= $_ENV['STORAGE_BUCKET'] ?>",
            messagingSenderId: "<?= $_ENV['MESSAGING_SENDER_ID'] ?>",
            appId: "<?= $_ENV['APP_ID'] ?>"
        };

        // Initialize Firebase
        const app = initializeApp(firebaseConfig);
        const auth = getAuth(app);

        //login function
        window.loginWithGoogle = function() {
            const provider = new GoogleAuthProvider();
            
            signInWithPopup(auth, provider)
                .then((result) => {
                    const user = result.user;

                    const userId = user.uid;
                    const userEmail = user.email;
                    const username = user.displayName;
                    const profilePic = user.photoURL;
                    const accessToken = user.accessToken;
                        
                    window.location.href = `oauth-callback.php?uid=${userId}&email=${encodeURIComponent(userEmail)}&username=${encodeURIComponent(username)}&profilePic=${encodeURIComponent(profilePic)}&accessToken=${accessToken}`;

                })
                .catch((error) => {
                    console.error('Error during login:', error);
                });
        }
    </script>
</body>
</html>
