<form>
    <input type="number" placeholder="Type a number" {{ ($item->getreadOnly() == "true")?"disabled":"" }} >
</form>