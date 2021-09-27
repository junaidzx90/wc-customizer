jQuery(function( $ ) {
	'use strict';

	$('.editinfo_tabs__radio').on('change', function () {
		let endpoint = $(this).val()+'-tab=true'
		window.history.pushState('', '', '?'+endpoint);
	});

	let profile_img = function (input) {
		
		if (input.files && input.files[0]) {
			let reader = new FileReader();
	
			reader.onload = function (e) {
				$(".profile-img img").attr('src', e.target.result );
			};
	
			reader.readAsDataURL(input.files[0]);
		}
	};

	let logo_img = function (input) {
		
		if (input.files && input.files[0]) {
			let reader = new FileReader();
	
			reader.onload = function (e) {
				$(".logo-img img").attr('src', e.target.result );
			};
	
			reader.readAsDataURL(input.files[0]);
		}
	};

	$("#profile_photo").on("change", function () {
		let img = $(this)
		let imgName = $(this)
			.val()
			.replace(/.*(\/|\\)/, "");
		let exten = imgName.substring(imgName.lastIndexOf(".") + 1);
		let expects = ["jpg", "jpeg", "png", "PNG", "JPG"];
	
		if (expects.indexOf(exten) == -1) {
			$(".profile-img img").attr('src', '');
			return false;
		}
		if ($("#profile_photo")[0].files[0].size > 2097152) {
			alert("We are sorry try to upload maximum 2MB!")
			return false;
		}

		if (img.val() == '') {
			$(".profile-img img").attr('src', '');
			return false;
		}

		profile_img(this);
	});

	$("#team_logo").on("change", function () {
		let img = $(this)
		let imgName = $(this)
			.val()
			.replace(/.*(\/|\\)/, "");
		let exten = imgName.substring(imgName.lastIndexOf(".") + 1);
		let expects = ["jpg", "jpeg", "png", "PNG", "JPG"];
	
		if (expects.indexOf(exten) == -1) {
			$(".logo-img img").attr('src', '');
			return false;
		}
		if ($("#team_logo")[0].files[0].size > 2097152) {
			alert("We are sorry try to upload maximum 2MB!")
			return false;
		}

		if (img.val() == '') {
			$(".logo-img img").attr('src', '');
			return false;
		}

		logo_img(this);
	});


});
