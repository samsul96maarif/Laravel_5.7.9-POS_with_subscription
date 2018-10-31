@extends('layouts/userMaster')

@section('title', 'Report')

@section('headline', 'Report By Items')

@section('content')

  <select class="click_menu" name="report">
    <option value="1">Report by Items</option>
    <option value="2">Report by Customers</option>
  </select>
  <div class="menu">
		<ul>
			<li><a class="klik_menu" id="home">HOME</a></li>
			<li><a class="klik_menu" id="tentang">TENTANG</a></li>
			<li><a class="klik_menu" id="tutorial">TUTORIAL</a></li>
			<li><a class="klik_menu" id="sosmed">SOSIAL MEDIA</a></li>
		</ul>
	</div>
  <br>
  <br>
  <script type="text/javascript">
	$(document).ready(function(){
		$('.klik_menu').click(function(){
			var menu = $(this).attr('id');
			if(menu == "home"){
				$('.badan').load('user/report/item.blade.php');
			}else if(menu == "tentang"){
				$('.badan').load('user/report/customer.blade.php');
			}else if(menu == "tutorial"){
				$('.badan').load('tutorial.php');
			}else if(menu == "sosmed"){
				$('.badan').load('sosmed.php');
			}
		});


		// halaman yang di load default pertama kali
		$('.badan').load('user/report/item.blade.php');

	});
</script>

@endsection
