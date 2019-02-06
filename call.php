<?php include "./partials/header.php"; ?>
    <div class="form">
    	<input type="text" id="username" placeholder="Bir kullanıcı adı yazınız">
    	<button id="ara"><i class="fa fa-phone"></i> Arama yap</button>
    </div>
	<video autoplay muted id="meVideo"></video>
	<video autoplay id="remoteVideo"></video>
	<div id="sonlandir">
		<i class="fa fa-ban"></i> Aramayı sonlandır
	</div>
    <script>
    	var peer = new Peer(); 
    	function showPeerClosed() {
    		Swal.fire({
    			html: "Arama sonlandırıldı.",
    			onClose: () => {
    				location.reload();
    			}
    		});
    	}
    	$("#ara").click(function(event) {
    		var username = $("#username").val();
    		if (username.length < 4) {
    			Swal.fire("Lütfen aramak istediğiniz kişinin en az 4 karakterden oluşan kullanıcı adını yazınız. ");
    		} else {
    			//Swal.fire("Lütfen bekleyiniz. Arama yapılıyor...");
    			$.post('./backend/getPeerId.php', {username: username}, function(remotePeerId, textStatus, xhr) {
					var getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia;
					navigator.getUserMedia({video: true, audio: true}, function(stream) {
				    	var meVideo = document.getElementById("meVideo");
				        var remoteVideo = document.getElementById("remoteVideo");
						var call = peer.call(remotePeerId, stream);
				    	peer.on('disconnected', function (argument) {
				            showPeerClosed()
				        });
				    	peer.on('close', function (argument) {
				            showPeerClosed();
				        });
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
    		}
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
<?php include "./partials/footer.php"; ?>