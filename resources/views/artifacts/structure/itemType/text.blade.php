<form>
    <input type="text" placeholder="Insira seu texto aqui" name="{{$item->getLinkId()}}" id="{{$item->getLinkId()}}" {{ ($item->getreadOnly() == "true")?"disabled":"" }}>
</form>
