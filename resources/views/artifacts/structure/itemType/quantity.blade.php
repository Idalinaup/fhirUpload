<form>
    <input type="number" placeholder="Type a number" name="{{$item->getLinkId()}}" id="{{$item->getLinkId()}}" {{ ($item->getreadOnly() == "true")?"disabled":"" }} >
</form>