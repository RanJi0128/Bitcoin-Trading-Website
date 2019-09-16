<!DOCTYPE html>
<html>
<head>
<script src="{{ asset('public/bitcoin/assets/node_modules/jquery/jquery-3.2.1.min.js') }}"></script>
<script src="{{ asset('public/bitcoin/assets/node_modules/popper/popper.min.js') }}"></script>
<script src="{{ asset('public/bitcoin/assets/node_modules/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/vue"></script>
</head>

<body>
    <div id = "vue_det">
        <h1>Firstname :@{{firstname}}</h1>
        <h1>Lastname : @{{lastname}}</h1>
        <div v-html = "htmlcontent"></div>
        <img v-bind:src="imgsrc" width="330px" heigh="330px">
    </div>
</body>
</html>
<script type = "text/javascript">
  var vm = new Vue({
    el: '#vue_det',
    data: {
        firstname : "Ria",
        lastname  : "Singh",
        htmlcontent : "<div><h1>this is the first template.</h1></div>",
        imgsrc:"{{asset('public/bitcoin/assets/images/favicon.png')}}"
    }
  })
  
</script>