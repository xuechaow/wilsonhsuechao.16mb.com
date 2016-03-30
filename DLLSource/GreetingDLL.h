#define DllExport   __declspec( dllexport )
#include <string>

namespace Greetings
{
    class HelloWorld
    {
    public:
        // Returns String "Hello World"
        DllExport static std::string Hello();
	};
}
