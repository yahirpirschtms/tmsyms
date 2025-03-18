<!DOCTYPE html>
<html>
<head>
    <title>No Tracker Shipment Warning</title>
</head>
<body>
    <h2>Shipment Picked Up Without Trackers</h2>
    <p><strong>Shipment ID:</strong> {{ $shipment->stm_id }}</p>

    <p><strong>Current Status:</strong> {{ $shipment->currentStatus->gntc_description }}</p>
    <p>This shipment has been picked up but does not have trackers.</p>
</body>
</html>
