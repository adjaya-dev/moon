<?php

declare(strict_types=1);

namespace Moon\Moon\Matchable;

use Psr\Http\Message\ServerRequestInterface;

class MatchableRequest implements MatchableRequestInterface
{
    private const REGEX_PREFIX = '::';
    private const REQUIRED_PLACEHOLDER_REGEX = '~\{(.*?)\}~';
    private const OPTIONAL_PLACEHOLDER_REGEX = '~\[((?>[^\[\]]+))*\]~';

    /**
     * @var ServerRequestInterface
     */
    private $request;

    /**
     * @var bool
     */
    private $patternMatched = false;

    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
    }

    public function match(array $criteria): bool
    {
        // Check if is a regex or transform it
        if (0 === \mb_strpos($criteria['pattern'], self::REGEX_PREFIX)) {
            $regex = \mb_substr($criteria['pattern'], 2);
        } else {
            $regex = $this->toRegex($criteria['pattern']);
        }

        /** @var bool $isPatternMatched */
        /** @var array $matches */
        [$isPatternMatched, $matches] = $this->matchByRegex($regex, $this->request->getUri()->getPath());

        if (!$isPatternMatched) {
            return false;
        }

        $this->patternMatched = true;

        if (!\in_array($this->request->getMethod(), $criteria['verbs'], true)) {
            return false;
        }

        foreach ($matches as $name => $value) {
            $this->request = $this->request->withAttribute($name, $value);
        }

        return true;
    }

    public function isPatternMatched(): bool
    {
        return $this->patternMatched;
    }

    public function request(): ServerRequestInterface
    {
        return $this->request;
    }

    /**
     * Return an array made by 2 elements:
     *  - A boolean value as value for know if the current pattern matches the path
     *  - An array with key/value as element to add to the request.
     */
    private function matchByRegex(string $pattern, string $path): array
    {
        $isPatternMatched = (bool) \preg_match_all("~^$pattern$~", $path, $matches);

        if (!$isPatternMatched) {
            return [$isPatternMatched, []];
        }

        foreach ($matches as $k => $match) {
            $match = \array_shift($match);
            if (\is_int($k) || empty($match)) {
                unset($matches[$k]);
                continue;
            }
            $matches[$k] = $match;
        }

        return [$isPatternMatched, $matches];
    }

    /**
     * Transform a pattern into a regex.
     */
    private function toRegex(string $pattern): string
    {
        while (\preg_match(self::OPTIONAL_PLACEHOLDER_REGEX, $pattern)) {
            $pattern = \preg_replace(self::OPTIONAL_PLACEHOLDER_REGEX, '($1)?', $pattern);
        }

        $pattern = \preg_replace_callback(self::REQUIRED_PLACEHOLDER_REGEX, function (array $match = []) {
            $match = \array_pop($match);
            $pos = \mb_strpos($match, self::REGEX_PREFIX);
            if (false !== $pos) {
                $parameterName = \mb_substr($match, 0, $pos);
                $parameterRegex = \mb_substr($match, $pos + 2);

                return "(?<$parameterName>$parameterRegex)";
            }

            return "(?<$match>[^/]+)";
        }, $pattern);

        return $pattern;
    }
}
