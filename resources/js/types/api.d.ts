export interface BaseExpr {
    type: string;
}

export interface BinaryExpr extends BaseExpr {
    lhs: Expr;
    fenceLhs: boolean;
    rhs: Expr;
    fenceRhs: Expr;
}

export interface UnaryExpr extends BaseExpr {
    operand: Expr;
    fenceOperand: boolean;
}

// Terminal

export interface NumericExpr extends BaseExpr {
    type: 'Numeric';
    value: string;
}

// Binary

export interface AddExpr extends BinaryExpr {
    type: 'Add';
}

export interface DivExpr extends BinaryExpr {
    type: 'Div';
}

export interface MulExpr extends BinaryExpr {
    type: 'Mul';
}

export interface PowExpr extends BinaryExpr {
    type: 'Pow';
}

export interface SubExpr extends BinaryExpr {
    type: 'Sub';
}

// Unary

export interface AbsExpr extends UnaryExpr {
    type: 'Abs';
}

export interface NegExpr extends UnaryExpr {
    type: 'Neg';
}

export interface SqrtExpr extends UnaryExpr {
    type: 'Sqrt';
}

export type Expr =
    | NumericExpr
    | AddExpr
    | DivExpr
    | MulExpr
    | SubExpr
    | AbsExpr
    | NegExpr
    | PowExpr
    | SqrtExpr;

type CursorPaginatedResponse<T> = T & {
    data: T[];
    links: {
        first: string | null;
        last: string | null;
        prev: string | null;
        next: string | null;
    };
    meta: {
        path: string;
        per_page: Number;
        next_cursor: string | null;
        prev_cursor: string | null;
    };
};

export type ErrorResponse = {
    message: string;
    errors?: { [key: string]: string[] };
};

export type Calculation = {
    id: string;
    expr: {
        raw: string;
        ast: Expr;
    };
    result: string;
};

export type StoreCalculationResponse = {
    data: Calculation;
};

export type ListCalculationsResponse = CursorPaginatedResponse<Calculation>;
