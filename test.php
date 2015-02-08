<html>
<head>

<script type="text/javascript">

  function hideRow(row, hideVal) {
    if (document.getElementById(row)) {
      var displayStyle = (hideVal!=true)? 'inline' : 'none' ;
      document.getElementById(row).style.display = displayStyle;
    }
  }

</script>

</head>
<body>

  <table border="1" cellpadding="10">
    <tr id="1">
      <td><b>Row 1</b></td>
      <td id="r1c1">Row-1 Col-1</td>
      <td id="r1c2">Row-1 Col-2</td>
      <td id="r1c3">Row-1 Col-3</td>
      <td id="r1c4">Row-1 Col-4</td>
    </tr>
    <tr id="2">
      <td><b>Row 2</b></td>
      <td id="r2c1">Row-2 Col-1</td>
      <td id="r2c2">Row-2 Col-2</td>
      <td id="r2c3">Row-2 Col-3</td>
      <td id="r2c4">Row-2 Col-4</td>
    </tr>
    <tr id="3">
      <td><b>Row 3</b></td>
      <td id="r3c1">Row-3 Col-1</td>
      <td id="r3c2">Row-3 Col-2</td>
      <td id="r3c3">Row-3 Col-3</td>
      <td id="r3c4">Row-3 Col-4</td>
    </tr>
    <tr id="4">
      <td><b>Row 4</b></td>
      <td id="r4c1">Row-4 Col-1</td>
      <td id="r4c2">Row-4 Col-2</td>
      <td id="r4c3">Row-4 Col-3</td>
      <td id="r4c4">Row-4 Col-4</td>
    </tr>

  </table>
<br>
<a href="#" onclick="hideRow(1, true);">Hide Row 1</a>
&nbsp;&nbsp;&nbsp;
<a href="#" onclick="hideRow(1, false);">Show Row 1</a>
<br>
<a href="#" onclick="hideRow(2, true);">Hide Row 2</a>
&nbsp;&nbsp;&nbsp;
<a href="#" onclick="hideRow(2, false);">Show Row 2</a>
<br>
<a href="#" onclick="hideRow(3, true);">Hide Row 3</a>
&nbsp;&nbsp;&nbsp;
<a href="#" onclick="hideRow(3, false);">Show Row 3</a>
<br>
<a href="#" onclick="hideRow(4, true);">Hide Row 4</a>
&nbsp;&nbsp;&nbsp;
<a href="#" onclick="hideRow(4, false);">Show Row 4</a>

</body>
</html>