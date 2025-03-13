// Función para registrar un préstamo
function registerLoan(userId, itemId) {
    // 3.1. Comprobar existencias ejemplar préstamo
    if (!checkItemAvailability(itemId)) {
        throw new Error("El ejemplar no está disponible para préstamo.");
    }

    // 3.2. Comprobar máximo préstamos en vigor del usuario
    const activeLoans = getActiveLoansForUser(userId);
    if (activeLoans.length >= 6) {
        throw new Error("El usuario ya tiene el máximo de 6 préstamos activos.");
    }

    // 3.3. Calcular fecha de devolución (3 semanas)
    const returnDate = new Date();
    returnDate.setDate(returnDate.getDate() + 21); // 3 semanas

    // Registrar el préstamo
    createLoanRecord(userId, itemId, returnDate);
}

// Función para listar documentos prestados para un usuario
function listLoansForUser(userId) {
    const loans = getLoansForUser(userId);
    return loans;
}

// Función para listar documentos no devueltos en fecha para un usuario
function listOverdueLoansForUser(userId) {
    const overdueLoans = getOverdueLoansForUser(userId);
    return overdueLoans;
} 