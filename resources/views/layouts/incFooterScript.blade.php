<!-- jQuery 3 -->
{{ Html::script(('public/AdminLTE-2.4.2/bower_components/jquery/dist/jquery.min.js')) }}
<!-- Bootstrap 3.3.7 -->
{{ Html::script(('public/AdminLTE-2.4.2/bower_components/bootstrap/dist/js/bootstrap.min.js')) }}
<!-- SlimScroll -->
{{ Html::script(('public/AdminLTE-2.4.2/bower_components/jquery-slimscroll/jquery.slimscroll.min.js')) }}
<!-- FastClick -->
{{ Html::script(('public/AdminLTE-2.4.2/bower_components/fastclick/lib/fastclick.js')) }}
<!-- AdminLTE App -->
{{ Html::script(('public/AdminLTE-2.4.2/dist/js/adminlte.min.js')) }}
<!-- AdminLTE for demo purposes -->
{{ Html::script(('public/AdminLTE-2.4.2/dist/js/demo.js')) }}
<!-- Bootstrap Tree -->
{{ Html::script(('public/js/minimal-tree-table.js')) }}

<script>
$(document).ready(function () {
	$('.sidebar-menu').tree()
})
</script>
