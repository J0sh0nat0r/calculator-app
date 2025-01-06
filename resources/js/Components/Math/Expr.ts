import { BinaryExpr, Expr } from '@/types/api';
import { defineComponent, h, VNode } from 'vue';

/**
 * Render an AST expression to MathML.
 *
 * @see https://developer.mozilla.org/en-US/docs/Web/MathML
 */
export default defineComponent<{ expr: Expr }>({
    name: 'Expr',
    props: {
        expr: Object,
    },
    setup(props) {
        return () => h('mrow', renderExpr(props.expr));
    },
});

function renderExpr(expr: Expr): VNode | VNode[] {
    switch (expr.type) {
        case 'Numeric':
            return h('mn', expr.value);
        case 'Abs':
            return fence(renderExpr(expr.operand), '|', '|');
        case 'Add':
            return renderBinaryExpr(expr, '+');
        case 'Div':
            return h('mfrac', [
                h('mrow', renderExpr(expr.lhs)),
                h('mrow', renderExpr(expr.rhs)),
            ]);
        case 'Mul':
            return renderBinaryExpr(expr, '×');
        case 'Neg': {
            const operand = renderExpr(expr.operand);

            return [
                h('mo', { form: 'prefix' }, '-'),
                expr.fenceOperand ? fence(operand) : operand,
            ].flat();
        }
        case 'Pow': {
            const lhs = renderExpr(expr.lhs);

            return h('msup', [
                h('mrow', expr.fenceLhs ? fence(lhs) : lhs),
                h('mrow', renderExpr(expr.rhs)),
            ]);
        }
        case 'Sqrt':
            return h('msqrt', renderExpr(expr.operand));
        case 'Sub':
            return renderBinaryExpr(expr, '-');
        default:
            throw new Error(`unknown expression type: ${expr['type']}`);
    }
}

function renderBinaryExpr(expr: BinaryExpr, operator: string): VNode[] {
    const lhs = renderExpr(expr.lhs);
    const rhs = renderExpr(expr.rhs);

    return [
        expr.fenceLhs ? fence(lhs) : lhs,
        h('mo', { form: 'infix' }, operator),
        expr.fenceRhs ? fence(rhs) : rhs,
    ].flat();
}

function fence(
    children: VNode | VNode[],
    left: string = '(',
    right: string = ')',
): VNode {
    return h(
        'mrow',
        [
            h('mo', { fence: true }, left),
            children,
            h('mo', { fence: true }, right),
        ].flat(),
    );
}
