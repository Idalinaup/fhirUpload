<form>
    <input type="date" name="{{$item->getLinkId()}}" id="{{$item->getLinkId()}}" {{ ($item->getreadOnly() == "true")?"disabled":"" }}>
</form>