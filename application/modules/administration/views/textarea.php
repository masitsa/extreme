
    <h1>TinyMCE Getting Started Guide</h1>
    <form method="post">
        <textarea id="mytextarea" rows="10" class="form-control"></textarea>
    </form>
	<script type="text/javascript" src="<?php echo base_url();?>assets/themes/tinymce/tinymce.min.js"></script>
    <script type="text/javascript">
        tinymce.init({
            selector: "#mytextarea"
        });
    </script>