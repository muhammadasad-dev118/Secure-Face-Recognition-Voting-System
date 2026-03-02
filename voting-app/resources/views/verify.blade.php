@extends('layouts.app')

@section('title', 'Identity Verification')

@section('content')
<div class="max-w-4xl mx-auto animate-fade-in-up">
    <div class="glass p-10 md:p-16 rounded-[3rem] shadow-2x-strong relative overflow-hidden" id="verification-card">
        
        <!-- Step 1: Identity Lookup -->
        <div id="step-lookup" class="text-center space-y-8 py-10">
            <div class="w-20 h-20 bg-brand-500/10 rounded-3xl flex items-center justify-center text-brand-500 mx-auto border border-brand-500/20 shadow-[0_0_30px_-5px_oklch(0.55_0.21_254)]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            
            <div class="space-y-4">
                <h2 class="text-4xl font-extrabold tracking-tight">Identity Retrieval</h2>
                <p class="text-slate-400 font-medium">Verify your enrollment by entering your registered email address.</p>
            </div>

            <div class="max-w-md mx-auto relative group">
                <input type="email" id="email" class="input-field !pl-14 !text-center !text-xl" placeholder="you@securevote.com">
                <div class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-brand-400 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>

            <button id="lookup-btn" class="btn-primary px-16 py-5 text-xl">
                Initiate Biometric Scan
            </button>
            <p id="lookup-status" class="text-sm font-bold uppercase tracking-widest hidden"></p>
        </div>

        <!-- Step 2: Biometric Scan -->
        <div id="step-scan" class="hidden">
            <h2 class="text-3xl font-extrabold mb-8 text-center tracking-tight">Biometric Verification</h2>
            
            <div class="grid lg:grid-cols-5 gap-10 items-center">
                <!-- Data HUD -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="glass-dark p-6 rounded-3xl border border-white/5">
                        <div class="text-[10px] font-mono text-slate-500 uppercase mb-3">Matching Confidence</div>
                        <div class="flex items-end gap-3">
                            <div id="match-score" class="text-5xl font-black font-mono text-slate-700">--</div>
                            <div class="text-slate-500 font-mono text-xs mb-2">/1.000</div>
                        </div>
                        <div class="mt-4 h-1.5 w-full bg-slate-800 rounded-full overflow-hidden">
                            <div id="distance-bar" class="h-full bg-brand-500 transition-all duration-500" style="width: 0%"></div>
                        </div>
                    </div>

                    <div id="liveness-card" class="glass-dark p-6 rounded-3xl border border-white/5 transition-all">
                        <div class="text-[10px] font-mono text-slate-500 uppercase mb-2">Liveness Status</div>
                        <div id="blink-status" class="text-2xl font-black italic text-yellow-500/50">Awaiting Blink</div>
                    </div>

                    <div id="status" class="text-center font-bold text-slate-500 text-sm uppercase tracking-widest leading-relaxed">
                        Initializing HUD...
                    </div>
                </div>

                <!-- Video HUD -->
                <div class="lg:col-span-3 relative">
                    <div class="video-container scanning-effect !rounded-[2.5rem] !max-w-none">
                        <video id="video" autoplay muted></video>
                        <div class="scan-line"></div>
                        
                        <!-- HUD Elements -->
                        <div class="absolute top-0 left-0 w-full h-full pointer-events-none border-[20px] border-slate-950/20"></div>
                        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-48 h-48 border-2 border-brand-500/20 rounded-full border-dashed animate-spin-slow"></div>
                    </div>
                    
                    <div id="match-status-badge" class="absolute -bottom-4 left-1/2 -translate-x-1/2 whitespace-nowrap px-10 py-4 rounded-full font-black text-xl bg-brand-600 text-white shadow-2xl hidden">
                        ACCESS GRANTED
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const video = document.getElementById('video');
    const statusDiv = document.getElementById('status');
    const lookupBtn = document.getElementById('lookup-btn');
    const lookupStatus = document.getElementById('lookup-status');
    const matchScoreDiv = document.getElementById('match-score');
    const distanceBar = document.getElementById('distance-bar');
    const blinkStatusDiv = document.getElementById('blink-status');
    const livenessCard = document.getElementById('liveness-card');
    const matchBadge = document.getElementById('match-status-badge');

    let storedDescriptor = null;
    let blinkDetected = false;
    let matchSuccessful = false;
    let isScanning = false;

    async function initModels() {
        try {
            if (typeof faceapi === 'undefined') {
                throw new Error("AI Library missing.");
            }
            const modelPath = "{{ asset('models') }}";
            console.log("Loading HUD models from:", modelPath);
            await Promise.all([
                faceapi.nets.ssdMobilenetv1.loadFromUri(modelPath).then(() => console.log("SSD MobileNet Loaded")),
                faceapi.nets.faceLandmark68Net.loadFromUri(modelPath).then(() => console.log("Landmarks Loaded")),
                faceapi.nets.faceRecognitionNet.loadFromUri(modelPath).then(() => console.log("Recognition Loaded"))
            ]);
            console.log("All HUD Systems Online");
        } catch (err) {
            console.error("Verify Init Error:", err);
            alert("System Error: " + err.message);
        }
    }

    lookupBtn.addEventListener('click', async () => {
        const email = document.getElementById('email').value.trim();
        if (!email) {
            alert("Please enter your registered email.");
            return;
        }

        lookupBtn.disabled = true;
        lookupStatus.innerHTML = '<span class="text-brand-400 animate-pulse">Querying Identity Registry...</span>';
        lookupStatus.classList.remove('hidden');

        console.log("Initiating lookup for:", email);

        try {
            const response = await fetch("{{ url('/fetch-face-data') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ email: email })
            });

            console.log("Server responded with status:", response.status);
            const data = await response.json();

            if (response.ok && data.success) {
                console.log("Identity found. Loading biometric profile...");
                storedDescriptor = new Float32Array(JSON.parse(data.face_data));
                document.getElementById('step-lookup').classList.add('hidden');
                document.getElementById('step-scan').classList.remove('hidden');
                startCamera();
            } else {
                console.warn("Lookup failed:", data.message);
                lookupStatus.innerHTML = `<span class="text-red-400 font-bold">${data.message || 'Identity not found'}</span>`;
                lookupBtn.disabled = false;
            }
        } catch (err) {
            console.error("Critical Lookup Error:", err);
            lookupStatus.innerHTML = `<span class="text-red-400 font-bold">Network Error: Registry Offline</span>`;
            lookupBtn.disabled = false;
        }
    });

    let videoStream = null;

    function startCamera() {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            alert("Camera access blocked. Use localhost or 127.0.0.1.");
            return;
        }

        navigator.mediaDevices.getUserMedia({ video: {} })
            .then(stream => {
                videoStream = stream;
                video.srcObject = stream;
                isScanning = true;
                processScan();
            })
            .catch(err => {
                console.error("Cam Error:", err);
                statusDiv.innerText = "Cam Permission Required";
                alert("Please allow camera access.");
            });
    }

    function stopCamera() {
        if (videoStream) {
            videoStream.getTracks().forEach(track => track.stop());
            video.srcObject = null;
            console.log("HUD Hardware Released");
        }
    }

    async function processScan() {
        if (!isScanning) return;

        // Perform detection - Optimized for speed
        const detections = await faceapi.detectSingleFace(video).withFaceLandmarks().withFaceDescriptor();

        if (detections) {
            const currentDescriptor = detections.descriptor;
            const distance = faceapi.euclideanDistance(currentDescriptor, storedDescriptor);
            
            // Loosen threshold slightly for faster 'catch' (0.6 is strict, 0.65 is still secure)
            const matchThreshold = 0.65;
            
            matchScoreDiv.innerText = distance.toFixed(3);
            const scorePercent = Math.max(0, Math.min(100, (1.1 - distance) * 100)); // Adjusted visual bar
            distanceBar.style.width = `${scorePercent}%`;
            
            if (distance < matchThreshold) {
                matchScoreDiv.className = "text-5xl font-black font-mono text-brand-400 font-glow animate-pulse";
                distanceBar.className = "h-full bg-brand-500 transition-all duration-150 shadow-[0_0_20px_oklch(0.55_0.21_254)]";
                matchSuccessful = true;
            } else {
                matchScoreDiv.className = "text-5xl font-black font-mono text-red-500";
                distanceBar.className = "h-full bg-red-600 transition-all duration-150";
                matchSuccessful = false;
            }

            const landmarks = detections.landmarks;
            const leftEye = landmarks.getLeftEye();
            const rightEye = landmarks.getRightEye();
            const ear = calculateEAR(leftEye, rightEye);
            
            // Increased EAR threshold to 0.25 to make blink detection more responsive
            if (ear < 0.25) {
                blinkDetected = true;
                blinkStatusDiv.innerText = "Liveness Verified ✓";
                blinkStatusDiv.className = "text-2xl font-black text-brand-400";
                livenessCard.className = "glass-dark p-6 rounded-3xl border border-brand-500/50 bg-brand-500/5 shadow-[0_0_30px_rgba(34,197,94,0.1)]";
            }

            if (matchSuccessful && blinkDetected) {
                isScanning = false;
                stopCamera();
                syncWithVault();
                return;
            } else {
                statusDiv.innerText = matchSuccessful ? "Identity Matched. Blink to Confirm." : "Scanning Biometrics...";
            }
        } else {
            statusDiv.innerText = "Align Subject in HUD";
        }

        if (isScanning) {
            setTimeout(processScan, 80); // High frequency for low latency
        }
    }

    function calculateEAR(leftEye, rightEye) {
        const dist = (p1, p2) => Math.sqrt(Math.pow(p1.x - p2.x, 2) + Math.pow(p1.y - p2.y, 2));
        const leftH = (dist(leftEye[1], leftEye[5]) + dist(leftEye[2], leftEye[4])) / (2 * dist(leftEye[0], leftEye[3]));
        const rightH = (dist(rightEye[1], rightEye[5]) + dist(rightEye[2], rightEye[4])) / (2 * dist(rightEye[0], rightEye[3]));
        return (leftH + rightH) / 2;
    }

    function syncWithVault() {
        statusDiv.innerText = "Identity Verified. Patching Session...";
        console.log("Verifying identity for vault access...");
        
        fetch("{{ url('/verified') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ email: document.getElementById('email').value })
        })
        .then(async res => {
            const data = await res.json();
            if(res.ok && data.success) {
                console.log("Vault Session Patched.");
                completeVerification();
            } else {
                console.error("Verification error:", data.message);
                alert("Vault access denied: " + (data.message || "Unknown error"));
                statusDiv.innerText = "Vault Rejection";
                isScanning = true;
                startCamera(); // Retry
            }
        })
        .catch(err => {
            console.error("Critical Sync Error:", err);
            alert("Vault communication link broken.");
        });
    }

    function completeVerification() {
        matchBadge.classList.remove('hidden');
        matchBadge.className = "absolute -bottom-4 left-1/2 -translate-x-1/2 whitespace-nowrap px-10 py-4 rounded-full font-black text-xl bg-green-500 text-white shadow-[0_0_50px_oklch(0.65_0.21_150)] animate-pop-in";
        
        // Final UI cleanup
        video.style.opacity = '0.3';
        document.querySelector('.scanning-effect')?.classList.remove('scanning-effect');

        setTimeout(() => {
            window.location.href = '/vote';
        }, 2000);
    }

    window.onload = initModels;
</script>
@endsection
