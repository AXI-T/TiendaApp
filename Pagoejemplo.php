    <link rel="stylesheet" href="css/bootstrap-4.0.0/dist/css/bootstrap.min.css">
    <link href="bootstrap4j/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <script src="bootstrap4j/js/jquery-1.12.4.min.js"></script>
    <script src="css/bootstrap-4.0.0/dist/js/bootstrap.min.js"></script>
    <div class="row g-4">
        <!-- Botón Contado -->
        <div class="col-md-6">
            <div class="card h-100 border-success metodo-pago-btn" data-metodo="contado">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="fas fa-money-bill-wave fa-3x text-success"></i>
                    </div>
                    <h3 class="card-title fw-bold text-success">CONTADO</h3>
                    <p class="card-text text-muted">Pago inmediato en efectivo</p>
                    <div class="mt-3">
                        <span class="badge bg-success">Rápido</span>
                        <span class="badge bg-success ms-1">Inmediato</span>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-success">
                    <small class="text-success">
                        <i class="fas fa-check-circle me-1"></i> Click para seleccionar
                    </small>
                </div>
            </div>
        </div>

        <!-- Botón Crédito -->
        <div class="col-md-6">
            <div class="card h-100 border-primary metodo-pago-btn" data-metodo="credito">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="fas fa-hand-holding-usd fa-3x text-primary"></i>
                    </div>
                    <h3 class="card-title fw-bold text-primary">CRÉDITO</h3>
                    <p class="card-text text-muted">Pago a plazos programados</p>
                    <div class="mt-3">
                        <span class="badge bg-primary">Plazos</span>
                        <span class="badge bg-primary ms-1">Registro</span>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-primary">
                    <small class="text-primary">
                        <i class="fas fa-info-circle me-1"></i> Requiere cliente registrado
                    </small>
                </div>
            </div>
        </div>
    </div>

    <style>
        .metodo-pago-btn {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .metodo-pago-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .metodo-pago-btn.selected {
            transform: scale(1.02);
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.1);
        }

    </style>
