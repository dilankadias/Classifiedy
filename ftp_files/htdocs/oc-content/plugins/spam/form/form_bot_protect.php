<!-- ANS SPECIAL FORM ENTRY -->
<div id="ans-hp-form">
  <input type="hidden" name="xcheckfield" value="ans" />
  <label for="whatSellA">What to sell</label><input value="greenTshirt" type="text" name="whatSellA" id="whatSellA" /><br />
  <label for="sellingToB">What to buy</label><input value="" type="text" name="sellingToB" id="sellingToB" /><br />
  <label for="userNameC">Stuff</label><input value="" type="text" name="userNameC" id="userNameC" />
  <label for="userAddressD">Name</label><input value="" type="text" name="userAddressD" id="userAddressD" />
  <label for="cityE">City</label><input value="" type="text" name="cityE" id="cityE" />
  <label for="yourAgeF">Fill</label><input value="" type="text" name="yourAgeF" id="yourAgeF" />
</div>

<style>#ans-hp-form {overflow:hidden;width:1px;height:1px;float:left;background:transparent;max-width:1px;max-height:1px;}</style>

<script>
$(document).ready(function() {
  var what = $('#whatSellA').val();
  $('#sellingToB').val(what);
  $('#yourAgeF').val((new Date).getFullYear());
});
</script>

