<form>
    <input type="url" id="url" name="url" placeholder="https://www.example.com" {{ ($item->getreadOnly() == "true")?"disabled":"" }}>
</form>
