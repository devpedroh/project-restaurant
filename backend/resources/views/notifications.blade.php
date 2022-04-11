<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Permitir notificações</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 10px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="content">
                <div class="title m-b-md">
                    Obrigado!
                </div>

                <div class="links">
                    Permita as notificações e, após isso, já pode fechar está janela.
                </div>
            </div>
        </div>
		<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
		<script src="https://www.gstatic.com/firebasejs/7.23.0/firebase.js" crossorigin="anonymous"></script>
		<script src="{{asset('js/ajax.js')}}" type="text/javascript" crossorigin="anonymous"></script>
		<script>
// 			if (Notification.permission !== 'denied') {
			    Notification.requestPermission();

				var url_string = window.location.href
				var url = new URL(url_string);
				var userToken = url.searchParams.get("token");

                var firebaseConfig = {
					apiKey: "AIzaSyDAQgEsEZ_43t_2NpDm-ARJLvHrz3Q_NsM",
					authDomain: "push-notification-41d35.firebaseapp.com",
					projectId: "push-notification-41d35",
					storageBucket: "push-notification-41d35.appspot.com",
					messagingSenderId: "858293320485",
					appId: "1:858293320485:web:5f707f56db42686b4438cc",
					measurementId: "G-GVBSKB1GHE"
				};

				firebase.initializeApp(firebaseConfig);
				const messaging = firebase.messaging();

				messaging
				.requestPermission()
				.then(function () {
					return messaging.getToken()
				})
				.then(function(token) {

					$.ajax({
						url: '{{ route("save-token") }}',
						type: 'POST',
						headers: {
							'Content-Type':'application/x-www-form-urlencoded; charset=UTF-8',
							'Authorization':'Bearer ' + userToken
						},
						data: {
							device_token: token
						},
						dataType: 'JSON',
						success: function (response) {
							console.log('Token updated')
						},
						error: function (err) {
							console.log('User Chat Token Error'+ err);
						},
					});

				}).catch(function (err) {
					console.log('User Chat Token Error'+ err);
				});

				messaging.onMessage(function(payload) {
					const noteTitle = payload.notification.title;
					const noteOptions = {
						body: payload.notification.body,
						icon: payload.notification.icon,
					};
					new Notification(noteTitle, noteOptions);
				});

		  //  }
		</script>
    </body>
</html>
