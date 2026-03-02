@extends('layouts.app')

@section('title', 'Identity Enrollment')

@section('content')
<div class="max-w-4xl mx-auto animate-fade-in-up">
    <div class="glass p-10 md:p-16 rounded-[3rem] shadow-2x-strong relative overflow-hidden">
        <!-- Decorative blobs -->
        <div class="absolute -top-20 -right-20 w-64 h-64 bg-brand-500/10 blur-3xl rounded-full"></div>
        
        <div class="relative z-10">
            <h2 class="text-4xl font-extrabold mb-4 text-center tracking-tight">Biometric Enrollment</h2>
            <p class="text-slate-400 mb-10 text-center font-medium max-w-lg mx-auto leading-relaxed">
                Connect your physical identity to the digital ballot. Please ensure your environment is well-lit.
            </p>

            <div class="grid lg:grid-cols-2 gap-12 items-start">
                <!-- Form Section -->
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-widest mb-3 ml-2">Full Name</label>
                        <input type="text" id="name" class="input-field" placeholder="John Doe">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-500 uppercase tracking-widest mb-3 ml-2">Secure Email</label>
                        <input type="email" id="email" class="input-field" placeholder="john@example.com">
                    </div>

                    <div class="pt-6">
                        <div id="status-card" class="glass-dark p-6 rounded-2xl border border-white/5 mb-6 text-center transform transition-all">
                            <div id="status-icon" class="mb-3">
                                 <div class="w-10 h-10 bg-slate-800 rounded-full mx-auto flex items-center justify-center text-slate-500">
                                     <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                     </svg>
                                 </div>
                            </div>
                            <div id="status" class="text-lg font-bold text-slate-300">Initializing Core...</div>
                        </div>

                        <button id="register-btn" disabled class="btn-primary w-full py-5 text-xl">
                            Register Biometrics
                        </button>
                    </div>
                </div>

                <!-- Preview Section -->
                <div class="relative">
                    <div class="video-container scanning-effect !aspect-square">
                        <video id="video" autoplay muted></video>
                        <div class="scan-line"></div>
                        
                        <!-- HUD Corners -->
                        <div class="absolute top-8 left-8 w-12 h-12 border-t-4 border-l-4 border-brand-500 rounded-tl-lg"></div>
                        <div class="absolute top-8 right-8 w-12 h-12 border-t-4 border-r-4 border-brand-500 rounded-tr-lg"></div>
                        <div class="absolute bottom-8 left-8 w-12 h-12 border-b-4 border-l-4 border-brand-500 rounded-bl-lg"></div>
                        <div class="absolute bottom-8 right-8 w-12 h-12 border-b-4 border-r-4 border-brand-500 rounded-br-lg"></div>
                    </div>
                    
                    <!-- HUD Metadata -->
                    <div class="absolute -bottom-4 -left-4 glass-dark px-4 py-2 rounded-xl text-[10px] font-mono text-slate-500 tracking-tighter">
                        ENROLLMENT_MODE::<span class="text-brand-400">0xFACE_ID</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const video = document.getElementById('video');
    const registerBtn = document.getElementById('register-btn');
    const statusDiv = document.getElementById('status');
    const statusCard = document.getElementById('status-card');

    async function setupFaceApi() {
        try {
            updateStatus("Checking Environment...", "loading");
            if (typeof faceapi === 'undefined') {
                throw new Error("AI Library missing. Please check your internet connection.");
            }
            
            // Determine the model path using Laravel asset helper
            const modelPath = "{{ asset('models') }}";
            console.log("Loading HUD models from:", modelPath);

            updateStatus("Loading AI Models...", "loading");
            await Promise.all([
                faceapi.nets.ssdMobilenetv1.loadFromUri(modelPath),
                faceapi.nets.faceLandmark68Net.loadFromUri(modelPath),
                faceapi.nets.faceRecognitionNet.loadFromUri(modelPath)
            ]);
            console.log("Models loaded successfully.");
            startVideo();
        } catch (err) {
            console.error("Setup Error:", err);
            updateStatus("System Error", "error");
            alert("Initialization failed: " + err.message);
        }
    }

    let videoStream = null;

    function startVideo() {
        updateStatus("Starting Camera...", "loading");
        
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            updateStatus("Insecure Context", "error");
            alert("Camera access is blocked. Please access the site via http://localhost:8000 or http://127.0.0.1:8000 to enable the camera.");
            return;
        }

        navigator.mediaDevices.getUserMedia({ video: { width: 640, height: 480 } })
            .then(stream => {
                videoStream = stream;
                video.srcObject = stream;
                updateStatus("Detecting Face...", "info");
                detectFace();
            })
            .catch(err => {
                console.error("Camera permissions error:", err);
                updateStatus("Camera Denied", "error");
                alert("Please grant camera permissions to use this feature.");
            });
    }

    function stopCamera() {
        if (videoStream) {
            videoStream.getTracks().forEach(track => track.stop());
            video.srcObject = null;
            console.log("Camera Hardware Released");
        }
    }

    function updateStatus(text, type) {
        statusDiv.innerText = text;
        statusCard.classList.remove('border-brand-500/50', 'border-red-500/50');
        statusDiv.classList.remove('text-brand-400', 'text-red-400');
        
        if (type === 'success') {
            statusCard.classList.add('border-brand-500/50', 'bg-brand-500/5');
            statusDiv.classList.add('text-brand-400');
        } else if (type === 'error') {
            statusCard.classList.add('border-red-500/50', 'bg-red-500/5');
            statusDiv.classList.add('text-red-400');
        }
    }

    let detectionInterval;
    let isAnchored = false;
    let lastDescriptor = null;

    async function detectFace() {
        if (isAnchored) return;
        
        detectionInterval = setInterval(async () => {
            const detections = await faceapi.detectSingleFace(video).withFaceLandmarks().withFaceDescriptor();
            if (detections) {
                if (!isAnchored) {
                    isAnchored = true;
                    lastDescriptor = Array.from(detections.descriptor);
                    updateStatus("Biometric Hash Verified. Ready.", "success");
                    registerBtn.disabled = false;
                    clearInterval(detectionInterval); 
                    console.log("Face locked and descriptor cached.");
                }
            } else {
                updateStatus("Subject Missing...", "info");
                registerBtn.disabled = true;
                isAnchored = false;
            }
        }, 500);
    }

    registerBtn.addEventListener('click', async () => {
        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;

        if (!name || !email) {
            updateStatus("Incomplete Profile", "error");
            alert("Please fill in your name and email.");
            return;
        }

        if (!lastDescriptor) {
            updateStatus("Acquiring Biometrics...", "loading");
            const detections = await faceapi.detectSingleFace(video).withFaceLandmarks().withFaceDescriptor();
            if (detections) {
                lastDescriptor = Array.from(detections.descriptor);
            } else {
                updateStatus("Face lost. Re-aligning...", "error");
                return;
            }
        }

        registerBtn.disabled = true;
        updateStatus("Syncing with Vault...", "loading");
        
        console.log("Attempting to register user:", email);

        fetch("{{ url('/register') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                name: name,
                email: email,
                face_data: JSON.stringify(lastDescriptor)
            })
        })
        .then(async response => {
            const data = await response.json();
            if (response.ok && data.success) {
                console.log("Registration Successful!");
                stopCamera();
                showSuccessScreen();
            } else {
                console.error("Server Error:", data.message);
                updateStatus(data.message || "Registration Failed", "error");
                alert("Error: " + (data.message || "Unknown server error"));
                registerBtn.disabled = false;
                isAnchored = false;
                lastDescriptor = null;
                detectFace(); 
            }
        })
        .catch(err => {
            console.error("Network/JS Error:", err);
            updateStatus("Vault sync failed.", "error");
            alert("Communication error: " + err.message);
            registerBtn.disabled = false;
        });
    });

    function showSuccessScreen() {
        const overlay = document.createElement('div');
        overlay.id = "success-vault-overlay";
        overlay.className = 'fixed inset-0 z-[100] flex items-center justify-center bg-slate-950/95 backdrop-blur-2xl animate-fade-in';
        overlay.innerHTML = `
            <div class="text-center space-y-8 p-12 glass rounded-[3rem] border-brand-500/30 max-w-lg mx-auto shadow-2x-strong animate-pop-in">
                <div class="w-32 h-32 bg-brand-500 text-white rounded-full flex items-center justify-center mx-auto shadow-[0_0_60px_oklch(0.55_0.21_254)]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div class="space-y-4">
                    <h2 class="text-5xl font-black text-white tracking-tighter uppercase">Vault Identity Created</h2>
                    <p class="text-xl text-slate-400 font-medium">Your biometric blueprint is encrypted and stored.</p>
                </div>
                <div class="pt-10 flex flex-col items-center gap-4">
                    <div class="inline-flex items-center gap-3 px-8 py-3 bg-brand-500/10 rounded-full text-brand-400 font-bold border border-brand-500/20">
                        <div class="w-2 h-2 bg-brand-400 rounded-full animate-pulse"></div>
                        Redirecting to Gateway
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(overlay);

        // Visual confirmation that camera is off
        video.style.opacity = '0';
        video.parentElement.classList.remove('scanning-effect');

        setTimeout(() => {
            window.location.href = '/';
        }, 3000);
    }

    window.onload = setupFaceApi;
</script>
@endsection
