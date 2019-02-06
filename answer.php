<?php include "./partials/header.php"; ?>
    <?php if (!isset($_SESSION['user'])): ?>
    <div class="form">
        <input type="text" id="username" placeholder="Bir kullanıcı adı yazınız">
        <br>
        <input type="text" id="password" placeholder="Bir şifre yazınız">
        <button id="kaydet">Giriş</button>
        <br>
        <small>Eğer böyle bir kullanıcı varsa giriş yapar, yoksa kayıt olur.</small>
    </div>
    
    <script>
        $("#kaydet").click(function(event) {
            var username = $("#username").val();
            var password = $("#password").val();
            $.post('./backend/login.php', {username: username, password: password}, function(data, textStatus, xhr) {
                data = JSON.parse(data);
                Swal.fire(data.message);
                if (data.status == "success") {
                    location.reload();
                }
            });
        });
    </script>
    <?php else: ?>
        <div class="form">
            <i class="fa fa-spinner fa-spin"></i> Bir arama bekleniyor..
            <br>
            <br>
            Kullanıcı adınız: <b><?= $_SESSION['user']['username'] ?></b>
        </div>
    
        <video autoplay muted id="meVideo"></video>
        <video autoplay id="remoteVideo"></video>
        
        <div id="sonlandir">
            <i class="fa fa-ban"></i> Aramayı sonlandır
        </div>
        <script>

            function showPeerClosed() {
                Swal.fire({
                    html: "Arama sonlandırıldı.",
                    onClose: () => {
                        location.reload();
                    }
                });
            }
            var peer = new Peer(); 

            
            peer.on('open', function(){
                $.post('./backend/changePeerId.php', 
                    {username: "<?=$_SESSION['user']['username']?>", peer_id: peer.id}, 
                    function(data, textStatus, xhr) {
                });
            });

            var getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia;
            peer.on('call', function(call) {
                call.on('disconnected', function (argument) {
                    showPeerClosed();
                });
                call.on('close', function (argument) {
                    showPeerClosed();
                });
                navigator.getUserMedia({video: true, audio: true}, function(stream) {
                    call.answer(stream); // Answer the call with an A/V stream.
                    var meVideo = document.getElementById("meVideo");
                    var remoteVideo = document.getElementById("remoteVideo");
                    call.on('stream', function(remoteStream) {
                        $(".form").hide();
                        meVideo.srcObject = stream;
                        remoteVideo.srcObject = remoteStream;
                        $("#meVideo").show();
                        $("#remoteVideo").show();
                        $("#sonlandir").show();
                    });
                }, function(err) {
                    console.log('Failed to get local stream' ,err);
                });
            });

            peer.on('disconnected', function (argument) {
                showPeerClosed();
            });
            peer.on('close', function (argument) {
                showPeerClosed();
            });
            $("#sonlandir").click(function(event) {
                peer.destroy();
            });
        </script>
    <?php endif; ?>
<?php include "./partials/footer.php"; ?>