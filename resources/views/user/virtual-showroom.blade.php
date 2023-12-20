@extends('user/master')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <title>Virtual Showroom</title>
    <style>
        body { margin: 0; }
        body, #showroom-container {
            margin: 0;
            padding: 0;
            overflow: hidden;
        }
        #showroom-container {
            width: 100%;
            height: 100%;
            cursor: pointer;
        }
        #chatbot__container{
            display:none;
        }
        #instructions {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: auto;
            padding: 20px;
            text-align: center;
            background-color: rgba(0, 0, 0, 0.7); /* Semi-transparent black background */
            color: white;
            font-size: 24px;
            cursor: pointer; /* Change cursor to indicate it's clickable */
            z-index: 100;
        }
        #crosshair {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 5px;
            height: 5px;
            background-color: red;
            border-radius: 50%;
        }
        canvas { display: block; } 
    </style>
<body>
    <div id="instructions">
        <span style="font-size: 24px; color:white">Click to play</span><br>
        Move: WASD<br>
        Look: Mouse Move<br>
        Click Button: Show Details <br>(click anywhere to close it)<br>
    </div>
    <div id="showroom-container"></div>
    <div id="fullscreen-btn" style="position: fixed; top: 110px; right: 10px; z-index: 1000; cursor: pointer; padding: 10px; background-color: #f1f1f1; border-radius: 5px;">
        Fullscreen
    </div>
    <div id="data-dialog" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 300px; padding: 20px; text-align: center; background-color: white; border-radius: 10px; box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2); z-index: 1001;">
        <div id="data-content">
        </div>
        <button onclick="closeDataDialog()">Close</button>
    </div>
    <div id="icon-container"></div>

    <div id="crosshair"></div>
    <audio id="background-music"></audio>

    <script src="https://cdn.jsdelivr.net/npm/three/build/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three/examples/js/controls/PointerLockControls.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three/examples/js/loaders/GLTFLoader.js"></script>

    <script type="text/javascript">
    var products = @json($products);
    </script>
    <script>

        const scene = new THREE.Scene();
        const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
        const renderer = new THREE.WebGLRenderer({ antialias: true });
        renderer.setSize(window.innerWidth, window.innerHeight);
        document.getElementById('showroom-container').appendChild(renderer.domElement);

        scene.background = new THREE.Color(0x000000);
        const ambientLight = new THREE.AmbientLight(0xffffff, 0.5);
        scene.add(ambientLight);
        const directionalLight = new THREE.DirectionalLight(0xffffff, 1);
        scene.add(directionalLight);

        const controls = new THREE.PointerLockControls(camera, renderer.domElement);
        document.getElementById('showroom-container').addEventListener('click', () => {
            controls.lock();
        });

        // WASD movement
        let moveForward = false;
        let moveBackward = false;
        let moveLeft = false;
        let moveRight = false;
        const velocity = new THREE.Vector3();
        const direction = new THREE.Vector3();
        const onKeyDown = function (event) {
            switch (event.code) {
                case 'KeyW':
                    moveForward = true;
                    break;
                case 'KeyA':
                    moveLeft = true;
                    break;
                case 'KeyS':
                    moveBackward = true;
                    break;
                case 'KeyD':
                    moveRight = true;
                    break;
            }
        };
        const onKeyUp = function (event) {
            switch (event.code) {
                case 'KeyW':
                    moveForward = false;
                    break;
                case 'KeyA':
                    moveLeft = false;
                    break;
                case 'KeyS':
                    moveBackward = false;
                    break;
                case 'KeyD':
                    moveRight = false;
                    break;
            }
        };
        document.addEventListener('keydown', onKeyDown);
        document.addEventListener('keyup', onKeyUp);

        const songs = [
            '/user/music/We Wish You a Merry Christmas.mp3',
            '/user/music/Jingle Bells.mp3',
            '/user/music/Last Christmas.mp3'
        ];
        let currentSongIndex = 0;

        const backgroundMusic = document.getElementById('background-music');

        function playSong(index) {
            backgroundMusic.src = songs[index];
            let playPromise = backgroundMusic.play();
        }

        backgroundMusic.addEventListener('ended', function() {
            currentSongIndex = (currentSongIndex + 1) % songs.length;
            playSong(currentSongIndex);
        });

        // Load GLB model
        const loader = new THREE.GLTFLoader();
        loader.load('user/images/virtual-showroom.glb', function (gltf) {
            const model = gltf.scene;
            model.scale.set(2,2,2);
            scene.add(model);
        }, undefined, function (error) {
            console.error('An error happened while loading the GLB file:', error);
        });

        camera.position.z = 5;
        camera.position.y = -9;
        let buttonMeshes = [];
        let searchIcons = new Map();
        let buttonCounter = 0;
        const bounds = {
            minX: -200,
            maxX: 200,
            minZ: -150,
            maxZ: 150
        };

        function checkBounds() {
            camera.position.x = Math.max(bounds.minX, Math.min(bounds.maxX, camera.position.x));
            camera.position.z = Math.max(bounds.minZ, Math.min(bounds.maxZ, camera.position.z));
        }

        function animate() {
            requestAnimationFrame(animate);

            if (controls.isLocked === true) {
                const delta = 0.03; // Adjust for speed

                velocity.x -= velocity.x * 10.0 * delta;
                velocity.z -= velocity.z * 10.0 * delta;

                direction.z = Number(moveForward) - Number(moveBackward);
                direction.x = Number(moveRight) - Number(moveLeft);
                direction.normalize(); // this ensures consistent movements in all directions
                if (moveForward || moveBackward) velocity.z -= direction.z * 400.0 * delta;
                if (moveLeft || moveRight) velocity.x -= direction.x * 400.0 * delta;

                controls.moveRight(-velocity.x * delta);
                controls.moveForward(-velocity.z * delta);
            }
            checkBounds();
            checkButtonVisibility();
            renderer.render(scene, camera);
        }

        function checkButtonVisibility() {
            const frustum = new THREE.Frustum();
            const projScreenMatrix = new THREE.Matrix4();
            projScreenMatrix.multiplyMatrices(camera.projectionMatrix, camera.matrixWorldInverse);
            frustum.setFromProjectionMatrix(projScreenMatrix);

            buttonMeshes.forEach(button => {
                const icon = searchIcons.get(button.userData.buttonId);

                if (icon && frustum.containsPoint(button.position)) {
                    const screenPosition = toScreenPosition(button, camera);
                    icon.style.display = 'block';
                    icon.style.left = `${screenPosition.x}px`;
                    icon.style.top = `${screenPosition.y}px`;
                } else if (icon) {
                    icon.style.display = 'none';
                }
            });
        }

        function toScreenPosition(obj, camera) {
            const vector = new THREE.Vector3();

            // Get the object's position in world space
            obj.updateMatrixWorld();
            vector.setFromMatrixPosition(obj.matrixWorld);

            // Project the position to screen space
            vector.project(camera);

            // Convert the normalized position (-1 to 1 on x and y) to the screen space
            const x = (vector.x * .5 + .5) * window.innerWidth;
            const y = (vector.y * -.5 + .5) * window.innerHeight;

            return { x, y };
        }

        function onWindowResize() {
            const headerHeight = document.querySelector('header').offsetHeight;
            const newHeight = window.innerHeight - headerHeight;
            renderer.setSize(window.innerWidth, newHeight);
            camera.aspect = window.innerWidth / newHeight;
            camera.fov = 60;
            camera.updateProjectionMatrix();
            renderer.render(scene, camera);
        }

        window.addEventListener('resize', onWindowResize);
        document.addEventListener('fullscreenchange', onWindowResize);
        onWindowResize();
        window.addEventListener('resize', onWindowResize, false);
        

        document.getElementById('showroom-container').addEventListener('click', () => {
            controls.lock();
        });
        document.getElementById('instructions').addEventListener('click', () => {
            controls.lock();
            playSong(currentSongIndex);
        });
        controls.addEventListener('lock', function () {
            instructions.style.display = 'none';
        });
        controls.addEventListener('unlock', function () {
            instructions.style.display = 'block';
            document.getElementById('background-music').pause();
        });
        const instructions = document.getElementById('instructions');
        instructions.style.display = 'block';


        function toggleFullscreenElements() {
            const header = document.querySelector('header');
            const footer = document.querySelector('footer');
            const fullscreenBtn = document.getElementById('fullscreen-btn');
            const isFullscreen = !!document.fullscreenElement;

            if (header) header.style.display = isFullscreen ? 'none' : '';
            if (footer) footer.style.display = isFullscreen ? 'none' : '';

            if (isFullscreen) {
                fullscreenBtn.textContent = 'Exit Fullscreen';
                fullscreenBtn.style.top = '30px';
                onWindowResize();
            } else {
                fullscreenBtn.textContent = 'Fullscreen';
                fullscreenBtn.style.top = '110px';
                onWindowResize();
            }

        }
        toggleFullscreenElements();
        document.getElementById('fullscreen-btn').addEventListener('click', () => {
            if (!document.fullscreenElement) {
                if (document.documentElement.requestFullscreen) {
                    document.documentElement.requestFullscreen();

                }
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                }
            }
        });
        document.addEventListener('fullscreenchange', toggleFullscreenElements);

        
        

        function createButtonForProduct(productId, position, rotation = null) {
            const buttonGeometry = new THREE.BoxGeometry(1, 0.5, 0.1);
            const buttonMaterial = new THREE.MeshBasicMaterial({ color: 0xff0000 });
            const buttonMesh = new THREE.Mesh(buttonGeometry, buttonMaterial);
            buttonMesh.position.copy(position);

            if (rotation) {
                buttonMesh.rotation.x = rotation.x;
                buttonMesh.rotation.y = rotation.y;
                buttonMesh.rotation.z = rotation.z;
            }

            buttonMesh.userData = { productId: productId };
            return buttonMesh;
        }

    function createButtonsAndIcons() {
        let buttonMesh1 = createButtonForProduct(1, new THREE.Vector3(-84.49791654174837, -10.958077154131513, -96.44773960113525));
        scene.add(buttonMesh1);
        buttonMeshes.push(buttonMesh1);

        let buttonMesh2 = createButtonForProduct(2, new THREE.Vector3(110.77511599564004, -10.958077154131513, 28.405015434525666));
        scene.add(buttonMesh2);
        buttonMeshes.push(buttonMesh2);
        const rotationY = -90 * (Math.PI / 180);
        let buttonMesh3 = createButtonForProduct(3, new THREE.Vector3(108.08246052220028, -12.955793754787527, -52.11963048990131), { x: 0, y: rotationY, z: 0 });
        scene.add(buttonMesh3);
        buttonMeshes.push(buttonMesh3);

        let buttonMesh4 = createButtonForProduct(4, new THREE.Vector3(-39.49421359757682, -11.135462049063047, -102.34293814704208));
        scene.add(buttonMesh4);
        buttonMeshes.push(buttonMesh4);


        let buttonMesh5 = createButtonForProduct(5, new THREE.Vector3(11.331793666754265, -11.101737410031829, -52.10018365862734), { x: 0, y: rotationY, z: 0 });
        scene.add(buttonMesh5);
        buttonMeshes.push(buttonMesh5);
        

        buttonMeshes.forEach(buttonMesh => {
            createSearchIconForButton(buttonMesh,buttonCounter++);
        });
    }
    function createSearchIconForButton(buttonMesh, buttonId) {
        let icon = document.createElement('i');
        icon.className = 'fas fa-search';
        icon.style.position = 'absolute';
        icon.style.display = 'none';
        icon.style.color = 'white'; 
        document.body.appendChild(icon); 

        searchIcons.set(buttonId, icon);
        buttonMesh.userData.buttonId = buttonId;
    }

    createButtonsAndIcons();

        const raycaster = new THREE.Raycaster();
        const mouse = new THREE.Vector2();
        function getCanvasRelativePosition(event) {
            const rect = renderer.domElement.getBoundingClientRect();
            return {
                x: ((event.clientX - rect.left) / rect.width) * 2 - 1,
                y: -((event.clientY - rect.top) / rect.height) * 2 + 1
            };
        }

        function onMouseMove(event) {
            const rect = renderer.domElement.getBoundingClientRect();
            mouse.x = ((event.clientX - rect.left) / rect.width) * 2 - 1;
            mouse.y = -((event.clientY - rect.top) / rect.height) * 2 + 1;
        }
        window.addEventListener('mousemove', onMouseMove, false);


        function onDocumentMouseDown(event) {
            if (controls.isLocked === true) {
                console.log("Mouse down event triggered");
                event.preventDefault();
                const centerX = (window.innerWidth / 2 - renderer.domElement.offsetLeft) / renderer.domElement.clientWidth * 2 - 1;
                const centerY = -(window.innerHeight / 2 - renderer.domElement.offsetTop) / renderer.domElement.clientHeight * 2 + 1;

                // Set the raycaster to the calculated center position
                raycaster.setFromCamera({ x: centerX, y: centerY }, camera);
                const intersects = raycaster.intersectObjects(scene.children);
                const intersectedObject = intersects[0].object;
                console.log("Intersected object:", intersectedObject);
                console.log("Intersected point:", intersects[0].point);
                console.log("Object position in world space:", intersectedObject.position);

                if (intersects.length > 0 && intersects[0].object.userData.productId) {
                    console.log("Product button clicked, ID:", intersects[0].object.userData.productId);
                    const productId = intersects[0].object.userData.productId;
                    const buttonMesh = intersects[0].object;
                    openDataDialog(productId, buttonMesh);
                } else {
                    console.log("No product button clicked");
                    close3DDialog();
                }
            }
        }
    document.addEventListener('mousedown', onDocumentMouseDown, false);

let currentDialog = null;
function createTextTexture(product, width = 512, height = 512) {
    const canvas = document.createElement('canvas');
    canvas.width = width;
    canvas.height = height;
    const context = canvas.getContext('2d');
    const padding = 20;
    const maxWidth = width - 2 * padding;
    const lineHeight = 24;

    // Background
    context.fillStyle = 'rgba(0, 0, 0, 0.7)'; // Semi-transparent black
    context.fillRect(0, 0, width, height);

    // Text styles
    context.font = 'bold 24px Arial';
    context.fillStyle = '#FFFFFF'; // White text
    context.textAlign = 'left';
    context.textBaseline = 'top';

    let yPosition = padding;

    // Product Name
    context.fillText(product.productName, padding, yPosition);
    yPosition += 40;

    context.font = '18px Arial';

    // Product Description
    context.fillText('Name: ' + product.productName, padding, yPosition);
    yPosition += lineHeight * 2;

    // Wrap text function
    function wrapText(context, text, x, y, maxWidth, lineHeight) {
        const words = text.split(' ');
        let line = '';

        for (let n = 0; n < words.length; n++) {
            const testLine = line + words[n] + ' ';
            const metrics = context.measureText(testLine);
            const testWidth = metrics.width;
            if (testWidth > maxWidth && n > 0) {
                context.fillText(line, x, y);
                line = words[n] + ' ';
                y += lineHeight;
            } else {
                line = testLine;
            }
        }
        context.fillText(line, x, y);
        return y + lineHeight; // Return the Y position after the last line of text
    }

    // Use wrapText function for description and update yPosition accordingly
    yPosition = wrapText(context, product.productDesc, padding, yPosition, width - 2 * padding, lineHeight);

    // Price
    context.fillText('Price: RM ' + product.price.toFixed(2), padding, yPosition);
    yPosition += 30; 

    // Colors
    context.fillText('Color: ' + product.colors, padding, yPosition);
    yPosition += 30;

    // Sizes
    const uniqueSizes = Array.from(new Set(product.sizes.split(',').map(size => size.trim()))).join(', ');
    context.fillText('Size: ' + uniqueSizes, padding, yPosition);
    yPosition += 50;

    const texture = new THREE.Texture(canvas);
    const img = new Image();
    const qrSize = 200;
    const qrXPosition = (width - qrSize) / 2;
    const qrYPosition = height - qrSize - 30; 
    img.onload = function() {
        context.drawImage(img, qrXPosition, qrYPosition, qrSize, qrSize);
        texture.needsUpdate = true; 
        if (callback) {
            callback(texture);
        }
    };
    img.src = '/user/images/product/' + product.productTryOnQR;

    texture.needsUpdate = true;
    return texture;
}


function create3DDialog(product, position) {
    console.log("Creating 3D dialog for product:", product);
    const dialogGroup = new THREE.Group();

    const dialogGeometry = new THREE.PlaneGeometry(2, 3);
    const textTexture = createTextTexture(product);
    const dialogMaterial = new THREE.MeshBasicMaterial({ map: textTexture, transparent: true });
    const dialogMesh = new THREE.Mesh(dialogGeometry, dialogMaterial);
    dialogGroup.add(dialogMesh);

    // Positioning the dialog
    dialogGroup.position.set(position.x, position.y + 1, position.z);
    dialogGroup.scale.set(1, 1, 1);
    dialogGroup.lookAt(camera.position);
    console.log("Created dialog group:", dialogGroup);
    return dialogGroup;
}


function openDataDialog(productId, buttonMesh) {
    console.log("Opening data dialog for product:", productId);
    var product = products.find(p => p.id === productId);
    if (!product) {
        console.error('Product not found!');
        return;
    }
    close3DDialog(); // Close any existing dialog
    const dialogPosition = calculateDialogPosition(buttonMesh, camera);

    currentDialog = create3DDialog(product, dialogPosition);
    currentDialog.scale.set(2, 2, 2); // Adjust the scale as needed
    scene.add(currentDialog);
    console.log("Dialog added. Scene children:", scene.children);
}
function calculateDialogPosition(buttonMesh, camera) {
    // Get the vector pointing from the button to the camera
    const toCamera = camera.position.clone().sub(buttonMesh.position);

    // Project this vector onto the horizontal plane (assuming Y-up world)
    toCamera.y = 0;
    toCamera.normalize();

    // Position the dialog a certain distance in front of the button, along this vector
    const distanceInFront = 2; // Adjust this distance as needed
    const positionInFront = buttonMesh.position.clone().add(toCamera.multiplyScalar(distanceInFront));

    return positionInFront;
}
function close3DDialog() {
    console.log("Closing data dialog");
    if (currentDialog) {
        scene.remove(currentDialog);
        currentDialog = null;
    }
}
animate();
    </script>
</body>
@endsection