print("=== Simple Python Chatbot ===")
print("Type 'bye' to exit.\n")

while True:
    user = input("You: ").lower()

    if user == "hello" or user == "hi":
        print("Bot: Hello! How are you?")

    elif "how are you" in user:
        print("Bot: I am fine. Thanks for asking!")

    elif "your name" in user:
        print("Bot: My name is Python Bot.")

    elif "bye" in user:
        print("Bot: Goodbye!")
        break

    else:
        print("Bot: Sorry, I don't understand.")