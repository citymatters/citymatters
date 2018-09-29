@extends('layout.app')

@section('title')
    DIY Hardware
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2>DIY Hardware</h2>
            <p>Unsere Messbox kann jeder für sich selber nachbauen. All unser Code ist Open Source und die Plattform kann kostenlos genutzt werden. Um für jeden engagierten Bürger den Einstieg in die Luftqualitätsmessung möglichst einfach zu gestalten, stellen wir die von uns getestete Hardware für euch vor:</p>
        </div>
        <div class="col-md-6">
            <h3>Mainboard</h3>
            <p>Als zentrales Hardwaremodul nutzen wir ein BeagleBone Black, welches die umliegenden Sensoren und Funkmodule koordiniert und  einen geregelten Messablauf sicherstellt. Es schließt an alle in der Messbox verwendeten Module direkt an. Die Kommunikation zwischen dem Temperatursensor und dem BeagleBone erfolgt dabei mittels eines proprietären Übertragungsprotokoll, während die restlichen Module per UART anschließen. </p>

        </div>

        <div class="col-md-6">
            <h3>Drahtlose Datenübertragung</h3>
            <p>Um die Datenübertragung nach dem Messen der Luftwerte zu ermöglichen wird in der Messbox sowie im Gateway ein Development-Kit CC1352P-P2 von Texas Instruments verbaut. </p>
            <h3>Gateway</h3>
            <p>Im Gateway ist ein BeagleBone Black und CC1352P-P2 verbaut.</p>
        </div>
        <div class="col-md-12">
            <h3>Verwendete Sensoren</h3>
            <p>Für die Messung des Feinstaubs wird ein SDS011 von Nova eingesetzt. Dieser misst Partikel mit einem Durchmesser von 2,5 Mikrometer und leitet mittels einer Näherung den Anteil an Partikeln mit einer Größe von 10 Mikrometer oder mehr an. </p>
            <p>Da die gemessene Menge an Feinstaubpartikeln direkt mit der Temperatur und Luftfeuchte korreliert, werden diese zusätzlichen  Werte mit einem DHT22-Sensor bestimmt. Das Modul kommuniziert in einem proprietären Protokoll mit einem der beiden Koprozessoren (PRU) des BeagleBones. </p>
            <p>Damit die geographischen Koordinaten der Messpunkte bestimmt werden können, ist ein MTK3339 GPS-Modul verbaut. Da die Sensorbox bei der Partikelmessung nicht in Bewegung befinden darf, bestimmt das GPS-Modul einerseits die derzeitige Geschwindigkeit sowie übermittelt die globalen Koordinaten, die darauf dem Messpunkt zugeordnet wird.</p>
        </div>
    </div>
@endsection