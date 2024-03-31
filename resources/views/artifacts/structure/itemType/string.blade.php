<form>
    <input type="text"  placeholder="Insira seu texto aqui" name="{{$itemChild->getLinkId()}}" id="{{$itemChild->getLinkId()}}" {{ ($itemChild->getreadOnly() == "true")?"disabled":"" }}>
</form>