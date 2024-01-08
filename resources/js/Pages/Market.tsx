import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import { MarketGladiator, PageProps } from "@/types";
import PrimaryButton from "@/Components/PrimaryButton";
import { useEffect, useMemo, useState } from "react";
import { useAxios } from "@/Hooks/useAxios";
import { useErrorHandler } from "@/Hooks/useErrorHandler";

export default function Market({
  auth,
  data,
}: PageProps<{ data: MarketGladiator[] }>) {
  const [gladiators, setGladiators] = useState<MarketGladiator[]>(data);
  const [isSubmitting, setIsSubmitting] = useState(false);
  const { axios } = useAxios();
  const { handleError } = useErrorHandler();

  const purchase = async (id: number) => {
    try {
      setIsSubmitting(true);
      await axios.post(route("market.purchase", { id }));
      setGladiators((old) =>
        old.map((g) => {
          if (g.id === id) {
            g.purchased = true;
          }
          return g;
        })
      );
    } catch (error) {
      handleError(error);
    } finally {
      setIsSubmitting(false);
    }
  };

  function Header() {
    const [counter, setCounter] = useState(
      // Seconds to midnight
      24 * 60 * 60 -
        new Date().getHours() * 60 * 60 -
        new Date().getMinutes() * 60 -
        new Date().getSeconds()
    );

    useEffect(() => {
      const timer =
        counter > 0 && setInterval(() => setCounter(counter - 1), 1000);
      return () => {
        if (timer) clearInterval(timer);
      };
    }, [counter]);

    const formattedCounter = useMemo(() => {
      const hours = Math.floor(counter / 3600);
      const minutes = Math.floor((counter - hours * 3600) / 60);
      const seconds = counter - hours * 3600 - minutes * 60;
      return `${hours.toString().padStart(2, "0")}:${minutes
        .toString()
        .padStart(2, "0")}:${seconds.toString().padStart(2, "0")}`;
    }, [counter]);

    return (
      <div className="w-full flex justify-between">
        <h1 className="text-2xl">Novices</h1>
        <p>
          Refreshes in <pre className="inline">{formattedCounter}</pre>
        </p>
      </div>
    );
  }

  function FlavorText() {
    return (
      <p className="mb-4">
        These men have never stepped foot on the battlefield, let alone in the
        arena. While they might be untested and untrained, they are also cheap
        and easily replaceable. Most are slaves, criminals, or prisoners of war,
        but that doesn't mean they can't be trained into a formidable gladiator.
      </p>
    );
  }

  function TableHeader() {
    return (
      <div className="py-2 text-lg grid grid-cols-8">
        <p>Name</p>
        <p className="text-center truncate text-ellipsis">Strength</p>
        <p className="text-center truncate text-ellipsis">Accuracy</p>
        <p className="text-center truncate text-ellipsis">Defense</p>
        <p className="text-center truncate text-ellipsis">Evasion</p>
      </div>
    );
  }

  function PurchasedDisplay() {
    return (
      <div className="w-full h-[45px] flex justify-center items-center border-b border-gray-600">
        <h3 className="text-2xl">Purchased</h3>
      </div>
    );
  }

  function GladiatorDisplay({ gladiator }: { gladiator: MarketGladiator }) {
    return (
      <div className="py-2 text-2xl grid grid-cols-8 border-b border-gray-600">
        <p>{gladiator.name}</p>
        <p className="text-center">{gladiator.strength}</p>
        <p className="text-center">{gladiator.accuracy}</p>
        <p className="text-center">{gladiator.defense}</p>
        <p className="text-center">{gladiator.evasion}</p>
        <p className="text-right">{gladiator.price} 𐆖</p>
        <div className="col-span-2 mx-auto flex items-center">
          <PrimaryButton
            className="py-0"
            disabled={isSubmitting}
            onClick={() => purchase(gladiator.id)}
          >
            Buy
          </PrimaryButton>
        </div>
      </div>
    );
  }

  return (
    <AuthenticatedLayout
      user={auth.user}
      header={
        <h2 className="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
          Market
        </h2>
      }
    >
      <Head title="Market" />

      <div className="py-12">
        <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div className="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900 dark:text-gray-100">
            <Header />
            <FlavorText />
            <TableHeader />
            <div>
              {gladiators?.map((g: MarketGladiator) => {
                return g.purchased ? (
                  <PurchasedDisplay key={g.id} />
                ) : (
                  <GladiatorDisplay key={g.id} gladiator={g} />
                );
              })}
            </div>
          </div>
        </div>
      </div>
    </AuthenticatedLayout>
  );
}
