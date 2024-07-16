<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body>
    <div class="dropdown">
        <select id="colorSelect" style="width: 200px;">
            <option value="orange">orange</option>
            <option value="white">white</option>
            <option value="purple">purple</option>
        </select>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#colorSelect').select2({
                tags: true,
                placeholder: "Select or add a color",
                allowClear: true
            });
        });
    </script>
</body>
</html>