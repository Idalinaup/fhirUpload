<form>
    <label>
        <input type="radio" name="boolean" value="true" onclick="updateValue(this.value)"> Yes
    </label>
    <label>
        <input type="radio" name="boolean" value="false" onclick="updateValue(this.value)"> No
    </label>
</form>

<script>
function updateValue(value) {
    console.log('Selected value: ' + value);
}
</script>