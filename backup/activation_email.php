<html>
<head>
    <title>Activatie email Fotografie Sandy</title>
</head>
<body>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td align="center">
                <h1>Beste <?php echo $user ?></h1><br>
                <img src="<?php echo base_url('assets/images/emaillogo.jpg') ?>" alt="logo">
                <br><br>
                <a href="<?php echo $link ?>" style="background-color:#dc69fe;border:1px solid #a850c1;border-radius:3px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:16px;line-height:44px;text-align:center;text-decoration:none;width:150px;-webkit-text-size-adjust:none;mso-hide:all;">Uw activatie link</a>
                <br><br>
                <p>Indien bovenstaande link niet werkt<br>
                <?php echo $link ?></p>
            </td>
        </tr>
    </table>
</body>
</html>