<?php

declare(strict_types=1);

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\toolbar\phpstan;

use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

use function in_array;
use function strtolower;

/**
 * Debug functions rule.
 *
 * @implements Rule<FuncCall>
 */
final class DebugFunctionsRule implements Rule
{
	/**
	 * Debug functions.
	 */
	protected const array DEBUG_FUNCTIONS = [
		'mako\toolbar\log',
	];

    /**
     * Returns the node type we want to check.
     */
    public function getNodeType(): string
    {
        return FuncCall::class;
    }

    /**
     * Processes the node and returns an error if a debug function is found.
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if (!$node->name instanceof Node\Name) {
            return [];
        }

        $resolvedName = $node->name->getAttribute('resolvedName');
        $functionName = strtolower((string) ($resolvedName ?? $node->name));

        if (in_array($functionName, self::DEBUG_FUNCTIONS)) {
            return [
                RuleErrorBuilder::message("Use of mako/toolbar debug function {$functionName}() found.")
				->tip(<<<'TIP'
				You can suppress this error with "@phpstan-ignore mako.toolbar.debugFunctions",
				though the function call should ideally be removed for optimal production performance.
				TIP)
				->identifier('mako.toolbar.debugFunctions')
				->build(),
            ];
        }

        return [];
    }
}
