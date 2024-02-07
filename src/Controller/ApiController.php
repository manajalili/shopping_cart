<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/items", name="view_cart", methods={"GET"})
     */
    public function viewCart(Request $request, SessionInterface $session): JsonResponse
    {
        // retrieves the cart items from the session
        $cartItems = $session->get('cart_items', []);

        return $this->json(['shopping_cart' => $cartItems]);
    }

    /**
     * @Route("/items", name="add_to_cart", methods={"POST"})
     */
    public function addToCart(Request $request, SessionInterface $session): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $item = $data['item'] ?? null;

        // checks if the item is provided in json
        if (!$item) {
            return $this->json(['message' => 'Invalid request. Item not provided.'], 400);
        }

        $cartItems = $session->get('cart_items', []);

        // checks if the item exists already in the cart
        if (in_array($item, $cartItems)) {
            return $this->json(['message' => 'Item already exists in the shopping cart'], 400);
        }

        // adds the new item to the cart
        $cartItems[] = $item;

        // stores the updated cart items back in the session
        $session->set('cart_items', $cartItems);

        return $this->json(['message' => 'Item added to the shopping cart successfully'], 200);
    }

    /**
    * @Route("/items/{item}", name="update_cart", methods={"PUT"})
    */
    public function updateCart(Request $request, SessionInterface $session, $item): JsonResponse
    {
        $cartItems = $session->get('cart_items', []);

        // checks to see if the item is available
        if (!in_array($item, $cartItems)) {
            return $this->json(['message' => 'Item not found in the shopping cart'], 404);
        }

        $data = json_decode($request->getContent(), true);
        $newItem = $data['newItem'] ?? null;

        // checks if the new item is provided in the json
        if (!$newItem) {
            return $this->json(['message' => 'Invalid request. New item not provided.'], 400);
        }

        // checks if new item is not a duplicate
        if (in_array($newItem, $cartItems)) {
            return $this->json(['message' => 'New item already exists in the shopping cart'], 400);
        }

        // updates the item with the new item
        $index = array_search($item, $cartItems);
        $cartItems[$index] = $newItem;

        $session->set('cart_items', $cartItems);

        return $this->json(['message' => 'Item is updated in the shopping cart'], 200);
    }

    /**
    * @Route("/items/{item}", name="remove_from_cart", methods={"DELETE"})
    */
    public function removeFromCart(Request $request, SessionInterface $session, $item): JsonResponse
    {
        $cartItems = $session->get('cart_items', []);

        $index = array_search($item, $cartItems);
        if ($index !== false) {
            unset($cartItems[$index]);
            // stores the updated cart items back in the session
            $session->set('cart_items', array_values($cartItems));

            return $this->json(['message' => 'Item removed from the shopping cart'], 200);
        }

        return $this->json(['message' => 'Item not found in the shopping cart'], 404);
    }

    /**
     * @Route("/clear-session", name="clear_session", methods={"GET"})
     */
    public function clearSession(SessionInterface $session): JsonResponse
    {
        $session->clear();

        return $this->json(['message' => 'Session cleared']);
    }
}