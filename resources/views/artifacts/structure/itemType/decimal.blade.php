<form>
    <input type="number" placeholder="Insert number here" {{ ($item->getreadOnly() == "true")?"disabled":"" }}>
</form>
