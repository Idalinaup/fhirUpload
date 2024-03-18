<form>
    <input type="number" placeholder="Enter your number here" {{ ($item->getreadOnly() == "true")?"disabled":"" }}>
</form>
