<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<titleDB Search</title>
</head>

<body>
	<?php echo form_open('administration/db_search/query_database');?>
    <textarea name="query"></textarea>
    <button type="submit">Query</button>
    <?php echo form_close();?>
</body>
</html>