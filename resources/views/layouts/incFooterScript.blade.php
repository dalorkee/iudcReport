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
<!-- DataTables -->
{{ Html::script(('public/AdminLTE-2.4.2/bower_components/datatables.net/js/jquery.dataTables.min.js')) }}
{{ Html::script(('public/AdminLTE-2.4.2/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')) }}
<script>
$(document).ready(function () {
	$('.sidebar-menu').tree();
	$('.loader').fadeOut('slow');
	$('#example0,#example1,#example2,#example3,#example4,#example5,#example6,#example7,#example8,#example9').DataTable({
		  'paging'      : false,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : false,
      'autoWidth'   : false,
			"aaSorting": [[ 1, "desc" ]]
	});
	$('#example10').DataTable({
		  'paging'      : false,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : false,
      'autoWidth'   : false,
			"aaSorting": [[ 1, "desc" ]]
	});
})
</script>
