<form>
    <input type="number" placeholder="Insert number here" name="{{$item->getLinkId()}}" id="{{$item->getLinkId()}}" {{ ($item->getreadOnly() == "true")?"disabled":"" }}>
</form>
