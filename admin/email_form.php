<form id="form1" name="form1" method="post" action="">
  <table width="486" cellspacing="0">
    <tr>
      <td width="133">First name </td>
      <td width="8">&nbsp;</td>
      <td width="323"><label>
        <input name="first_name" type="text" id="first_name" value="<?=$first_name;?>" />
      </label></td>
    </tr>
    <tr>
      <td>Last name </td>
      <td>&nbsp;</td>
      <td><label>
        <input name="last_name" type="text" id="last_name" value="<?=$last_name;?>" />
      </label></td>
    </tr>
    <tr>
      <td>Title</td>
      <td>&nbsp;</td>
      <td><label>
        <select name="title" id="title" >
          <option value="null">Select Title</option>
          <option value="Mr">Mr</option>
          <option value="Mrs">Mrs</option>
          <option value="Miss">Miss</option>
          <option value="Doctor">Doctor</option>
          <option value="Professor">Professor</option>
        </select>
      </label></td>
    </tr>
    <tr>
      <td>Email Address </td>
      <td>&nbsp;</td>
      <td><input name="email" type="text" id="email" value="<?=$email;?>"/></td>
    </tr>
    <tr>
      <td>Is Active? </td>
      <td>&nbsp;</td>
      <td><input name="is_active" type="checkbox" id="is_active" value="1" checked=""/></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><label>
        <input type="submit" name="Submit" value="Submit" />
      </label></td>
    </tr>
  </table>
</form>