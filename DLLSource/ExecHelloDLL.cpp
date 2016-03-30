#include <iostream>

#include "GreetingDLL.h"

using namespace std;

int main()
{

	cout << Greetings::HelloWorld::Hello().c_str() << endl;

}
